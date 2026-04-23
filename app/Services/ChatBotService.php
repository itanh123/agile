<?php

namespace App\Services;

class ChatBotService
{
    private array $faqResponses = [];
    private array $quickReplies = [];

    public function __construct()
    {
        $this->initializeFAQ();
        $this->initializeQuickReplies();
    }

    private function initializeFAQ(): void
    {
        $this->faqResponses = [
            // Giờ làm việc
            ['keywords' => ['giờ', 'mở cửa', 'đóng cửa', 'làm việc', 'thời gian', 'work', 'open', 'close', 'time'],
                'response' => '🕐 **Giờ làm việc của Pet Care Center:**\n\n• Thứ 2 - Thứ 6: 8:00 - 19:00\n• Thứ 7 - CN: 9:00 - 17:00\n• Ngày lễ: Nghỉ\n\nBạn có thể đặt lịch trực tuyến 24/7!'],

            // Giá dịch vụ
            ['keywords' => ['giá', 'chi phí', 'bao nhiêu', 'tiền', 'price', 'cost', 'fee', 'phí'],
                'response' => '💰 **Bảng giá dịch vụ:**\n\n• Cắt tỉa lông: 150.000 - 500.000đ\n• Tắm rửa: 80.000 - 250.000đ\n• Tiêm phòng: 200.000 - 500.000đ\n• Spa: 300.000 - 800.000đ\n• Khám sức khỏe: 150.000đ\n\nXem chi tiết: /services'],

            // Đặt lịch
            ['keywords' => ['đặt', 'lịch', 'hẹn', 'booking', 'appointment', 'schedule', 'make'],
                'response' => '📅 **Đặt lịch hẹn:**\n\n1. Đăng nhập tài khoản\n2. Chọn dịch vụ tại /services\n3. Chọn thú cưng của bạn\n4. Chọn ngày và giờ phù hợp\n5. Xác nhận đặt lịch\n\nBạn cần hỗ trợ đặt lịch không?'],

            // Hủy lịch
            ['keywords' => ['hủy', 'cancel', 'hủy lịch', 'bỏ lịch'],
                'response' => '❌ **Hủy lịch hẹn:**\n\nBạn có thể hủy lịch hẹn trước ít nhất 2 giờ:\n\n1. Đăng nhập vào tài khoản\n2. Vào "Lịch hẹn của tôi"\n3. Chọn lịch hẹn cần hủy\n4. Nhấn "Hủy lịch"\n\nLưu ý: Không hủy sau thời gian cho phép có thể bị tính phí.'],

            // Thanh toán
            ['keywords' => ['thanh toán', 'payment', 'pay', 'tiền', 'chuyển khoản', 'vnpay', 'momo'],
                'response' => '💳 **Thanh toán:**\n\nChúng tôi hỗ trợ:\n• Tiền mặt khi đến cửa hàng\n• VNPay (ATM, Internet Banking)\n• Ví MoMo\n• Chuyển khoản ngân hàng\n\nThanh toán online nhận **ưu đãi 5%**!'],

            // Thú cưng
            ['keywords' => ['thú cưng', 'pet', 'chó', 'mèo', 'pets', 'dog', 'cat', 'animal'],
                'response' => '🐾 **Dịch vụ cho thú cưng:**\n\nChúng tôi phục vụ:\n• 🐕 Chó: Cắt tỉa, tắm, spa, tiêm phòng\n• 🐱 Mèo: Cắt lông, vệ sinh, chăm sóc\n• 🐹 Các loài khác: Hamster, thỏ, chim...\n\nMỗi thú cưng được chăm sóc riêng biệt!'],

            // Liên hệ
            ['keywords' => ['liên hệ', 'contact', 'số điện thoại', 'phone', 'email', 'địa chỉ', 'address', 'hỗ trợ'],
                'response' => '📞 **Liên hệ với chúng tôi:**\n\n• 📱 Hotline: 0901 234 567\n• 📧 Email: info@petcare.vn\n• 📍 Địa chỉ: 123 Đường ABC, Quận 1, TP.HCM\n\nChúng tôi luôn sẵn sàng hỗ trợ bạn 24/7!'],

            // Voucher/Khuyến mãi
            ['keywords' => ['voucher', 'mã giảm', 'khuyến mãi', 'giảm giá', 'coupon', 'discount', 'sale', 'promo'],
                'response' => '🎁 **Khuyến mãi:**\n\n• Mã giảm 10% cho đơn hàng đầu tiên\n• Giảm 5% khi thanh toán online\n• Tặng voucher 50k cho khách giới thiệu\n\nXem thêm tại trang khuyến mãi hoặc hỏi tôi về mã giảm giá cụ thể!'],

            // Dịch vụ tại nhà
            ['keywords' => ['tại nhà', 'home', 'nhà', 'giao tận nơi', 'pickup', 'delivery'],
                'response' => '🏠 **Dịch vụ tại nhà:**\n\nChúng tôi cung cấp:\n• Dịch vụ tại cửa hàng (mặc định)\n• Dịch vụ tại nhà (có phí phụ)\n• Nhận thú qua nhân viên shop\n\nLiên hệ để biết thêm chi tiết về dịch vụ tại nhà!'],

            // Tiêm phòng
            ['keywords' => ['tiêm', 'phòng', 'vaccine', 'vaccination', 'shot', 'injection'],
                'response' => '💉 **Dịch vụ tiêm phòng:**\n\n• Vaccine dại (cho chó)\n• Vaccine 5 bệnh (cho chó)\n• Vaccine 4 bệnh (cho mèo)\n• Vaccine phòng dại\n\nGiá: 200.000 - 500.000đ/tiêm\nĐặt lịch ngay để được tiêm đúng lịch!'],

            // Spa
            ['keywords' => ['spa', 'grooming', 'làm đẹp', 'skin', 'beauty', 'massage'],
                'response' => '✨ **Dịch vụ Spa:**\n\n• Tắm nước thơm\n• Massage thư giãn\n• Dưỡng lông bóng mượt\n• Trị ve, bọ chét\n• Cắt móng\n\nGiá: 300.000 - 800.000đ\nĐặt lịch để thú cưng được spa thoải mái!'],

            // Hướng dẫn
            ['keywords' => ['hướng dẫn', 'guide', 'help', 'how', 'làm sao', 'cách', 'sử dụng'],
                'response' => '📖 **Hướng dẫn sử dụng:**\n\n1. **Đăng ký/Đăng nhập** - Tạo tài khoản để đặt dịch vụ\n2. **Thêm thú cưng** - Quản lý hồ sơ pet của bạn\n3. **Chọn dịch vụ** - Xem và chọn dịch vụ phù hợp\n4. **Đặt lịch** - Chọn ngày giờ và thanh toán\n5. **Theo dõi** - Xem trạng thái lịch hẹn\n\nBạn cần hỗ trợ thêm gì?'],
        ];
    }

    private function initializeQuickReplies(): void
    {
        $this->quickReplies = [
            ['label' => '📅 Đặt lịch', 'value' => 'tôi muốn đặt lịch'],
            ['label' => '💰 Bảng giá', 'value' => 'giá dịch vụ là bao nhiêu'],
            ['label' => '📞 Liên hệ', 'value' => 'số điện thoại liên hệ'],
            ['label' => '❓ Hướng dẫn', 'value' => 'hướng dẫn sử dụng'],
            ['label' => '🏠 Dịch vụ tại nhà', 'value' => 'dịch vụ tại nhà'],
        ];
    }

    public function getResponse(string $userMessage): array
    {
        $userMessage = strtolower(trim($userMessage));
        $bestMatch = null;
        $bestScore = 0;

        foreach ($this->faqResponses as $faq) {
            $score = 0;
            foreach ($faq['keywords'] as $keyword) {
                if (str_contains($userMessage, strtolower($keyword))) {
                    $score++;
                }
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMatch = $faq;
            }
        }

        if ($bestScore > 0) {
            return [
                'type' => 'faq',
                'content' => $bestMatch['response'],
                'confidence' => $bestScore,
                'quick_replies' => $this->quickReplies,
            ];
        }

        return [
            'type' => 'fallback',
            'content' => "🤖 **Trợ lý Pet Care:**\n\nXin chào! Tôi có thể hỗ trợ bạn về:\n• 🗓️ Đặt lịch hẹn\n• 💰 Bảng giá dịch vụ\n• 🐾 Chăm sóc thú cưng\n• 📞 Thông tin liên hệ\n\nBạn cần hỗ trợ gì? Gõ 'hướng dẫn' để xem hướng dẫn sử dụng!",
            'confidence' => 0,
            'quick_replies' => $this->quickReplies,
        ];
    }

    public function getQuickReplies(): array
    {
        return $this->quickReplies;
    }
}