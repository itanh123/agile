<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function show(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);

        $payment = $booking->payment;
        $bankAccounts = [];

        return view('customer.payments.show', compact('booking', 'payment', 'bankAccounts'));
    }

    public function selectMethod(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        abort_if($booking->payment_status === 'paid', 403);

        return view('customer.payments.select', compact('booking'));
    }

    public function process(Request $request, Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        abort_if($booking->payment_status === 'paid', 403);

        $method = $request->input('method', 'cash');

        return match ($method) {
            'vnpay' => $this->processVNPay($booking),
            'momo' => $this->processMoMo($booking),
            'transfer' => $this->processTransfer($booking),
            'cash' => $this->processCash($booking),
            default => back()->with('error', 'Phương thức thanh toán không hợp lệ.'),
        };
    }

    private function processVNPay(Booking $booking)
    {
        if (!config('services.vnpay.tmn_code')) {
            return back()->with('error', 'Cổng thanh toán VNPay chưa được cấu hình.');
        }

        $result = $this->paymentService->processVNPay($booking);

        return redirect($result['redirect_url']);
    }

    private function processMoMo(Booking $booking)
    {
        if (!config('services.momo.partner_code')) {
            return back()->with('error', 'Cổng thanh toán MoMo chưa được cấu hình.');
        }

        $result = $this->paymentService->processMoMo($booking);

        return redirect($result['redirect_url']);
    }

    private function processTransfer(Booking $booking)
    {
        $result = $this->paymentService->processBankTransfer($booking);

        return view('customer.payments.transfer', [
            'booking' => $booking,
            'payment' => $result['payment'],
            'bankAccounts' => $result['bank_accounts'],
            'instruction' => $result['instruction'],
        ]);
    }

    private function processCash(Booking $booking)
    {
        $payment = $this->paymentService->createPayment($booking, 'cash');
        $payment->update(['status' => 'pending']);

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', 'Đã tạo đơn thanh toán tiền mặt. Vui lòng thanh toán khi đến cửa hàng.');
    }

    public function vnpayReturn(Request $request)
    {
        $responseCode = $request->input('vnp_ResponseCode');

        if ($responseCode === '00') {
            $transactionCode = $request->input('vnp_TxnRef');
            $payment = \App\Models\Payment::where('transaction_code', $transactionCode)->first();

            if ($payment) {
                $this->paymentService->markAsPaid($payment, $request->all());

                return redirect()->route('customer.bookings.show', $payment->booking)
                    ->with('success', 'Thanh toán VNPay thành công!');
            }
        }

        return redirect()->route('customer.dashboard')
            ->with('error', 'Thanh toán thất bại hoặc bị hủy. Mã lỗi: ' . $responseCode);
    }

    public function vnpayIpn(Request $request)
    {
        $data = $request->all();
        $isValid = $this->paymentService->verifyVNPayResponse($data);

        if ($isValid) {
            $transactionCode = $data['vnp_TxnRef'];
            $payment = \App\Models\Payment::where('transaction_code', $transactionCode)->first();

            if ($payment) {
                if ($payment->status === 'pending') {
                    if ($data['vnp_ResponseCode'] === '00') {
                        $this->paymentService->markAsPaid($payment, $data);
                    } else {
                        $this->paymentService->markAsFailed($payment, 'Giao dịch thất bại: ' . $data['vnp_ResponseCode']);
                    }
                }
                return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
            }
            return response()->json(['RspCode' => '01', 'Message' => 'Order Not Found']);
        }
        return response()->json(['RspCode' => '97', 'Message' => 'Invalid Checksum']);
    }

    public function momoReturn(Request $request)
    {
        $resultCode = $request->input('resultCode');

        if ($resultCode == '0') {
            $transactionCode = $request->input('orderId');
            $payment = \App\Models\Payment::where('transaction_code', $transactionCode)->first();

            if ($payment) {
                $this->paymentService->markAsPaid($payment, $request->all());

                return redirect()->route('customer.bookings.show', $payment->booking)
                    ->with('success', 'Thanh toán MoMo thành công!');
            }
        }

        return redirect()->back()
            ->with('error', 'Thanh toán thất bại hoặc bị hủy. Mã lỗi: ' . $resultCode);
    }

    public function confirmTransfer(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        abort_if($booking->payment_status === 'paid', 403);

        $payment = $booking->payment;
        if ($payment) {
            $payment->update([
                'status' => 'pending',
                'note' => 'Chờ xác nhận từ admin. Khách hàng đã xác nhận đã chuyển kho���n.',
            ]);
        }

        Notification::send(
            auth()->id(),
            'payment_pending',
            'Yêu cầu xác nhận thanh toán',
            "Khách hàng đã xác nhận chuyển khoản cho booking #{$booking->booking_code}. Vui lòng kiểm tra và xác nhận.",
            route('admin.bookings.show', $booking),
            'bank',
            'warning'
        );

        return redirect()->route('customer.bookings.show', $booking)
            ->with('info', 'Chúng tôi đã nhận được xác nhận của bạn. Thanh toán sẽ được xác minh trong vài phút.');
    }
}