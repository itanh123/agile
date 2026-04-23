<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentService
{
    public const METHOD_VNPAY = 'vnpay';
    public const METHOD_MOMO = 'momo';
    public const METHOD_TRANSFER = 'transfer';
    public const METHOD_CASH = 'cash';

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_REFUNDED = 'refunded';

    public function createPayment(Booking $booking, string $method = self::METHOD_CASH): Payment
    {
        return Payment::create([
            'booking_id' => $booking->id,
            'transaction_code' => $this->generateTransactionCode($method),
            'payment_method' => $method,
            'status' => self::STATUS_PENDING,
            'amount' => $booking->total_amount,
        ]);
    }

    public function generateTransactionCode(string $method): string
    {
        $prefix = match ($method) {
            self::METHOD_VNPAY => 'VNP',
            self::METHOD_MOMO => 'MOMO',
            self::METHOD_TRANSFER => 'TRF',
            default => 'CASH',
        };

        return $prefix . '-' . strtoupper(Str::random(8));
    }

    public function processVNPay(Booking $booking): array
    {
        $payment = $this->createPayment($booking, self::METHOD_VNPAY);

        $vnp_TmnCode = config('services.vnpay.tmn_code');
        $vnp_HashSecret = config('services.vnpay.hash_secret');
        $vnp_Url = config('services.vnpay.url');
        $vnp_ReturnUrl = config('services.vnpay.return_url');

        $vnp_TxnRef = $payment->transaction_code;
        $vnp_Amount = (int)($booking->total_amount * 100);

        $inputData = [
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $vnp_TmnCode,
            'vnp_Amount' => $vnp_Amount,
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => now()->format('YmdHis'),
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => '127.0.0.1',
            'vnp_Locale' => 'vn',
            'vnp_OrderInfo' => 'Thanh toan don hang ' . $booking->booking_code,
            'vnp_OrderType' => 'billpayment',
            'vnp_ReturnUrl' => $vnp_ReturnUrl,
            'vnp_TxnRef' => $vnp_TxnRef,
        ];

        ksort($inputData);

        $hashdata = '';
        $query = '';
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . '=' . urlencode($value) . '&';
        }

        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= '?' . $query . 'vnp_SecureHash=' . $vnpSecureHash;

        return [
            'success' => true,
            'payment' => $payment,
            'redirect_url' => $vnp_Url,
        ];
    }

    public function processMoMo(Booking $booking): array
    {
        $payment = $this->createPayment($booking, self::METHOD_MOMO);

        $endpoint = config('services.momo.endpoint');
        $partnerCode = config('services.momo.partner_code');
        $accessKey = config('services.momo.access_key');
        $secretKey = config('services.momo.secret_key');

        $orderId = $payment->transaction_code;
        $amount = (int)$booking->total_amount;
        $orderInfo = "Thanh toán đơn hàng #{$booking->booking_code}";
        $returnUrl = config('services.momo.return_url');
        $notifyUrl = config('services.momo.notify_url');

        $requestId = Str::uuid()->toString();
        $requestType = 'payWithATM';

        $rawData = "accessKey={$accessKey}&amount={$amount}&extraData=&ipnUrl={$notifyUrl}&orderId={$orderId}&orderInfo={$orderInfo}&partnerCode={$partnerCode}&redirectUrl={$returnUrl}&requestId={$requestId}&requestType={$requestType}";

        $signature = hash_hmac('sha256', $rawData, $secretKey);

        $payload = [
            'accessKey' => $accessKey,
            'partnerCode' => $partnerCode,
            'requestType' => $requestType,
            'notifyUrl' => $notifyUrl,
            'returnUrl' => $returnUrl,
            'orderId' => $orderId,
            'amount' => $amount,
            'orderInfo' => $orderInfo,
            'requestId' => $requestId,
            'signature' => $signature,
            'extraData' => '',
        ];

        return [
            'success' => true,
            'payment' => $payment,
            'redirect_url' => $endpoint . '?' . http_build_query($payload),
        ];
    }

    public function processBankTransfer(Booking $booking): array
    {
        $payment = $this->createPayment($booking, self::METHOD_TRANSFER);

        $bankAccounts = [
            'vietcombank' => [
                'bank' => 'Vietcombank',
                'account' => '1234567890',
                'name' => 'CÔNG TY TNHH PET CARE',
                'branch' => 'Chi nhánh Hà Nội',
            ],
            'mbbank' => [
                'bank' => 'MB Bank',
                'account' => '0987654321',
                'name' => 'CÔNG TY TNHH PET CARE',
                'branch' => 'Chi nhánh HCM',
            ],
        ];

        return [
            'success' => true,
            'payment' => $payment,
            'bank_accounts' => $bankAccounts,
            'instruction' => "Vui lòng chuyển khoản số tiền {$booking->total_amount} VNĐ vào tài khoản bên dưới và ghi rõ nội dung: {$booking->booking_code}",
        ];
    }

    public function markAsPaid(Payment $payment, array $transactionData = []): void
    {
        $payment->update([
            'status' => self::STATUS_PAID,
            'paid_at' => now(),
            'note' => json_encode($transactionData),
        ]);

        if ($payment->booking) {
            $payment->booking->update(['payment_status' => 'paid']);
        }
    }

    public function markAsFailed(Payment $payment, ?string $reason = null): void
    {
        $payment->update([
            'status' => self::STATUS_FAILED,
            'note' => $reason,
        ]);

        if ($payment->booking) {
            $payment->booking->update(['payment_status' => 'failed']);
        }
    }

    public function refund(Payment $payment, ?string $reason = null): bool
    {
        if ($payment->status !== self::STATUS_PAID) {
            return false;
        }

        $payment->update([
            'status' => self::STATUS_REFUNDED,
            'note' => "Refund: " . ($reason ?? 'Customer requested'),
        ]);

        if ($payment->booking) {
            $payment->booking->update(['payment_status' => 'refunded']);
        }

        return true;
    }

    public function verifyVNPayResponse(array $data): bool
    {
        $vnp_HashSecret = config('services.vnpay.hash_secret');
        $inputData = [];

        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'vnp_') && $key !== 'vnp_SecureHash') {
                $inputData[$key] = $value;
            }
        }

        ksort($inputData);
        $hashdata = http_build_query($inputData);
        $secureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        return $secureHash === ($data['vnp_SecureHash'] ?? '');
    }
}