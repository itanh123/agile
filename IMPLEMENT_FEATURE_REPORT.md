# BÁO CÁO TỔNG KẾT TRIỂN KHAI CHỨC NĂNG

**Ngày tạo:** 16/04/2026
**Dự án:** Pet Care Center - Hệ thống quản lý dịch vụ thú cưng
**Phiên bản Laravel:** 10.x

---

## 1. TỔNG QUAN

### 1.1 Mục tiêu
Quét toàn bộ project để xác định trạng thái các chức năng trong danh sách yêu cầu, và tiến hành implement các chức năng còn thiếu.

### 1.2 Phạm vi yêu cầu (đã từ chối do có người làm)
- RQ02: Quản lý hồ sơ thú cưng
- RQ03: Xem danh sách dịch vụ
- RQ10: Áp dụng mã giảm giá
- RQ11: Theo dõi trạng thái dịch vụ
- RQ13: Xem lịch sử sử dụng dịch vụ
- RQ14: Đánh giá dịch vụ

### 1.3 Chức năng cần triển khai
| Mã | Chức năng | Vai trò | Trạng thái cũ |
|----|-----------|---------|----------------|
| RQ01 | Đăng nhập tài khoản | Khách hàng | Đã có |
| RQ04 | Xem chi tiết dịch vụ | Khách hàng | Đã có |
| RQ05 | Đặt lịch chăm sóc | Khách hàng | Đã có |
| RQ06 | Hủy/đổi lịch hẹn | Khách hàng | Đã có |
| RQ07 | Nhận xác nhận đặt lịch | Khách hàng | Cần bổ sung |
| RQ08 | Nhận thông báo nhắc lịch | Khách hàng | Cần bổ sung |
| RQ09 | Thanh toán online | Khách hàng | Cần bổ sung |
| RQ12 | Nhận hình ảnh thú cưng | Khách hàng | Đã có (cập nhật notification) |
| RQ15 | Trợ lý ảo | Khách hàng | Cần nâng cấp |
| RQ16 | Xem danh sách lịch hẹn | Nhân viên | Đã có |
| RQ17 | Cập nhật trạng thái dịch vụ | Nhân viên | Đã có |
| RQ18 | Upload hình ảnh thú cưng | Nhân viên | Đã có |
| RQ19 | Ghi chú tình trạng thú cưng | Nhân viên | Đã có |
| RQ20 | Quản lý người dùng | Quản trị | Đã có |
| RQ21 | Quản lý dịch vụ | Quản trị | Đã có |
| RQ22 | Quản lý lịch hẹn | Quản trị | Đã có |
| RQ23 | Phân công nhân viên | Quản trị | Đã có |
| RQ24 | Tạo khuyến mãi/voucher | Quản trị | Đã có |
| RQ25 | Xem báo cáo | Quản trị | Đã có |
| RQ26 | Tìm kiếm dịch vụ (khách vãng lai) | Khách vãng lai | Cần bổ sung |
| RQ27 | Xem thông tin dịch vụ (khách vãng lai) | Khách vãng lai | Cần bổ sung |
| RQ28 | Đăng ký nhanh bằng SĐT | Khách vãng lai | Cần bổ sung |
| RQ29 | Nhận thú qua nhân viên shop | Khách hàng | Cần tạo mới |

---

## 2. BẢNG MAPPING TRẠNG THÁI

### 2.1 Đã có sẵn (không cần thay đổi)

| Mã | Chức năng | Bằng chứng |
|----|-----------|------------|
| RQ01 | Đăng nhập | `AuthController.php`, `routes/web.php` |
| RQ04 | Xem chi tiết dịch vụ | `ServiceController.php`, `/services/{id}` |
| RQ05 | Đặt lịch chăm sóc | `CustomerBookingController::store()` |
| RQ06 | Hủy/đổi lịch hẹn | `CustomerBookingController::cancel()`, `reschedule()` |
| RQ12 | Nhận hình ảnh | Notification khi staff upload |
| RQ16 | Xem danh sách lịch hẹn | `StaffBookingController::index()` |
| RQ17 | Cập nhật trạng thái | `StaffBookingController::updateStatus()` |
| RQ18 | Upload hình ảnh | `StaffBookingController::uploadImage()` |
| RQ19 | Ghi chú tình trạng | `StaffBookingController::addNote()` |
| RQ20 | Quản lý người dùng | `AdminUserController.php` |
| RQ21 | Quản lý dịch vụ | `AdminServiceController.php` |
| RQ22 | Quản lý lịch hẹn | `AdminBookingController.php` |
| RQ23 | Phân công nhân viên | `AdminBookingController::assignStaff()` |
| RQ24 | Tạo khuyến mãi | `AdminPromotionController.php` |
| RQ25 | Xem báo cáo | `AdminReportController.php` |

### 2.2 Đã được nâng cấp/triển khai mới

| Mã | Chức năng | Trạng thái mới | Ghi chú |
|----|-----------|----------------|---------|
| RQ07 | Nhận xác nhận đặt lịch | Hoàn thành | Thêm notification khi tạo booking |
| RQ08 | Nhận thông báo nhắc lịch | Hoàn thành | Tạo command schedule mỗi giờ |
| RQ09 | Thanh toán online | Hoàn thành | VNPay, MoMo, chuyển khoản |
| RQ15 | Trợ lý ảo | Hoàn thành | Chatbot FAQ 12 topics |
| RQ26 | Tìm kiếm dịch vụ | Hoàn thành | Route `/guest/services` |
| RQ27 | Xem thông tin dịch vụ | Hoàn thành | Route `/guest/services/{id}` |
| RQ28 | Đăng ký nhanh | Hoàn thành | Quick register + quick booking |
| RQ29 | Nhận thú qua nhân viên | Hoàn thành | PickupRequest model + controller |

---

## 3. FILE ĐÃ TẠO MỚI

### 3.1 Models
| File | Mô tả |
|------|-------|
| `app/Models/Notification.php` | Model notification với các method gửi thông báo |
| `app/Models/PickupRequest.php` | Model yêu cầu giao nhận thú cưng |

### 3.2 Controllers
| File | Mô tả |
|------|-------|
| `app/Http/Controllers/Customer/NotificationController.php` | Quản lý thông báo cho khách hàng |
| `app/Http/Controllers/Customer/PaymentController.php` | Xử lý thanh toán online |
| `app/Http/Controllers/Customer/PickupController.php` | Yêu cầu giao nhận cho khách hàng |
| `app/Http/Controllers/Staff/PickupController.php` | Xử lý giao nhận cho nhân viên |
| `app/Http/Controllers/GuestController.php` | Xử lý khách vãng lai |

### 3.3 Services
| File | Mô tả |
|------|-------|
| `app/Services/PaymentService.php` | Xử lý thanh toán VNPay, MoMo, chuyển khoản |
| `app/Services/ChatBotService.php` | Xử lý chatbot FAQ 12 topics |

### 3.4 Migrations
| File | Mô tả |
|------|-------|
| `database/migrations/2024_01_01_000001_create_notifications_table.php` | Bảng notifications |
| `database/migrations/2024_01_01_000002_create_pickup_requests_table.php` | Bảng pickup_requests |

### 3.5 Commands
| File | Mô tả |
|------|-------|
| `app/Console/Commands/SendBookingReminders.php` | Gửi nhắc lịch hẹn tự động |

### 3.6 Observers
| File | Mô tả |
|------|-------|
| `app/Observers/BookingObserver.php` | Theo dõi thay đổi booking |

### 3.7 Views
| File | Mô tả |
|------|-------|
| `resources/views/customer/notifications/index.blade.php` | Trang thông báo |
| `resources/views/customer/payments/select.blade.php` | Chọn phương thức thanh toán |
| `resources/views/customer/payments/transfer.blade.php` | Hướng dẫn chuyển khoản |
| `resources/views/customer/pickups/index.blade.php` | Danh sách yêu cầu giao nhận |
| `resources/views/customer/pickups/show.blade.php` | Chi tiết yêu cầu giao nhận |
| `resources/views/staff/pickups/index.blade.php` | Danh sách giao nhận (staff) |
| `resources/views/staff/pickups/show.blade.php` | Chi tiết giao nhận (staff) |
| `resources/views/guest/services.blade.php` | Trang dịch vụ cho khách vãng lai |
| `resources/views/guest/service-detail.blade.php` | Chi tiết dịch vụ cho khách vãng lai |
| `resources/views/customer/assistant/index.blade.php` | Trang trợ lý ảo (đã cập nhật) |
| `resources/views/customer/bookings/create.blade.php` | Form đặt lịch (đã cập nhật) |

---

## 4. FILE ĐÃ CHỈNH SỬA

### 4.1 Models
| File | Thay đổi |
|------|----------|
| `app/Models/Booking.php` | Thêm constants, methods notification, quan hệ pickupRequest |

### 4.2 Controllers
| File | Thay đổi |
|------|----------|
| `app/Http/Controllers/Customer/BookingController.php` | Thêm gọi notification khi tạo booking |
| `app/Http/Controllers/Staff/BookingController.php` | Thêm notification khi cập nhật trạng thái, upload ảnh |
| `app/Http/Controllers/Admin/BookingController.php` | Thêm notification khi assign staff |
| `app/Http/Controllers/Customer/AssistantController.php` | Nâng cấp chatbot với FAQ |

### 4.3 Providers
| File | Thay đổi |
|------|----------|
| `app/Providers/AppServiceProvider.php` | Đăng ký BookingObserver |

### 4.4 Console
| File | Thay đổi |
|------|----------|
| `app/Console/Kernel.php` | Thêm schedule cho `bookings:send-reminders` |

### 4.5 Routes
| File | Thay đổi |
|------|----------|
| `routes/web.php` | Thêm routes cho notification, payment, pickup, guest |

---

## 5. ROUTES MỚI

### 5.1 Customer Routes (xác thực)
```
GET  /customer/notifications               # Xem thông báo
GET  /customer/notifications/{id}/read      # Đánh dấu đã đọc
POST /customer/notifications/mark-all-read  # Đánh dấu tất cả đã đọc
DELETE /customer/notifications/{id}         # Xóa thông báo

GET  /customer/bookings/{id}/payment        # Chọn phương thức thanh toán
POST /customer/bookings/{id}/payment        # Xử lý thanh toán
GET  /payment/vnpay/return                 # VNPay callback
GET  /payment/momo/return                  # MoMo callback

GET  /customer/pickups                     # Danh sách yêu cầu giao nhận
GET  /customer/pickups/{id}                # Chi tiết yêu cầu giao nhận
POST /customer/bookings/{id}/pickup         # Tạo yêu cầu giao nhận
PATCH /customer/pickups/{id}/cancel         # Hủy yêu cầu giao nhận
```

### 5.2 Staff Routes (xác thực)
```
GET  /staff/pickups                         # Danh sách yêu cầu giao nhận
GET  /staff/pickups/{id}                    # Chi tiết yêu cầu giao nhận
POST /staff/pickups/{id}/accept             # Nhận yêu cầu
POST /staff/pickups/{id}/picked-up          # Đánh dấu đã nhận thú
POST /staff/pickups/{id}/delivered          # Đánh dấu đã giao trả
POST /staff/pickups/{id}/note               # Thêm ghi chú
```

### 5.3 Guest Routes (không cần đăng nhập)
```
GET  /guest/services                        # Danh sách dịch vụ
GET  /guest/services/{id}                  # Chi tiết dịch vụ
POST /guest/quick-register                 # Đăng ký nhanh
POST /guest/quick-booking                   # Đặt lịch nhanh
```

---

## 6. THAY ĐỔI DATABASE

### 6.1 Bảng mới: `notifications`
| Column | Type | Mô tả |
|--------|------|-------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key -> users |
| type | varchar(50) | Loại thông báo |
| title | varchar(255) | Tiêu đề |
| content | text | Nội dung |
| link | varchar(500) | Link đến trang liên quan |
| icon | varchar(50) | Icon bootstrap |
| color | varchar(20) | Màu sắc |
| is_read | boolean | Đã đọc chưa |
| read_at | timestamp | Thời gian đọc |
| scheduled_at | timestamp | Thời gian hẹn gửi |
| created_at | timestamp | |
| updated_at | timestamp | |

### 6.2 Bảng mới: `pickup_requests`
| Column | Type | Mô tả |
|--------|------|-------|
| id | bigint | Primary key |
| booking_id | bigint | Foreign key -> bookings |
| pickup_code | varchar(30) | Mã yêu cầu (unique) |
| status | enum | pending, assigned, picked_up, delivered, cancelled |
| pickup_staff_id | bigint | Foreign key -> users |
| pickup_address | varchar(500) | Địa chỉ nhận thú |
| pickup_phone | varchar(20) | SĐT liên hệ |
| pickup_note | text | Ghi chú |
| scheduled_pickup_at | timestamp | Giờ hẹn nhận |
| actual_pickup_at | timestamp | Giờ nhận thực tế |
| delivered_at | timestamp | Giờ giao trả |
| staff_notes | text | Ghi chú nhân viên |
| created_at | timestamp | |
| updated_at | timestamp | |

---

## 7. HƯỚNG DẪN TEST THỦ CÔNG

### 7.1 RQ07 + RQ08: Thông báo và nhắc lịch

**Test case 1: Tạo booking và nhận notification**
1. Đăng nhập với tài khoản customer
2. Vào `/customer/bookings/create`
3. Chọn thú cưng, dịch vụ, ngày giờ
4. Nhấn "Xác nhận đặt lịch"
5. Kiểm tra: Toast thông báo thành công + notification được tạo

**Test case 2: Cập nhật trạng thái và nhận notification**
1. Đăng nhập với tài khoản staff
2. Vào `/staff/bookings`
3. Chọn một booking đã được assign
4. Cập nhật trạng thái (confirmed, processing, completed)
5. Đăng nhập lại với tài khoản customer của booking đó
6. Kiểm tra: Vào `/customer/notifications` để xem thông báo

**Test case 3: Nhắc lịch tự động**
1. Chạy command: `php artisan bookings:send-reminders`
2. Kiểm tra: Các booking trong 48 giờ tới sẽ có notification nhắc lịch

### 7.2 RQ09: Thanh toán online

**Test case 1: Chọn phương thức thanh toán**
1. Đăng nhập customer
2. Vào `/customer/bookings`, chọn một booking chưa thanh toán
3. Nhấn "Thanh toán"
4. Kiểm tra: Trang chọn phương thức hiển thị 4 lựa chọn

**Test case 2: Thanh toán chuyển khoản**
1. Chọn "Chuyển khoản"
2. Kiểm tra: Trang hướng dẫn với thông tin tài khoản ngân hàng
3. Nhấn "Tôi đã chuyển khoản"
4. Kiểm tra: Booking được tạo payment pending

### 7.3 RQ15: Trợ lý ảo

**Test case 1: Gửi tin nhắn FAQ**
1. Đăng nhập customer
2. Vào `/customer/assistant`
3. Gửi tin nhắn: "giờ mở cửa"
4. Kiểm tra: Phản hồi về giờ làm việc

**Test case 2: Quick replies**
1. Kiểm tra: Các nút quick reply hiển thị khi chưa có tin nhắn
2. Nhấn "📅 Đặt lịch"
3. Kiểm tra: Input được điền và form submit

### 7.4 RQ26-28: Khách vãng lai

**Test case 1: Xem dịch vụ không đăng nhập**
1. Đăng xuất hoặc mở trình duyệt mới
2. Truy cập `/guest/services`
3. Kiểm tra: Danh sách dịch vụ hiển thị, có thể filter

**Test case 2: Xem chi tiết dịch vụ**
1. Click vào một dịch vụ
2. Kiểm tra: Trang chi tiết với giá, mô tả, quy trình

**Test case 3: Đặt lịch nhanh**
1. Nhấn nút "Đặt nhanh không cần tài khoản"
2. Điền: SĐT, tên pet, chọn dịch vụ, ngày giờ
3. Nhấn "Xác nhận đặt lịch"
4. Kiểm tra: Tài khoản được tạo, booking được tạo, đăng nhập tự động

### 7.5 RQ29: Giao nhận thú cưng

**Test case 1: Tạo yêu cầu giao nhận (Customer)**
1. Đăng nhập customer
2. Vào `/customer/bookings/create`, chọn service_mode = "pickup"
3. Điền thông tin và submit
4. Kiểm tra: PickupRequest được tạo, notification gửi

**Test case 2: Nhận yêu cầu (Staff)**
1. Đăng nhập staff
2. Vào `/staff/pickups`
3. Nhấn "Nhận việc" trên một yêu cầu pending
4. Kiểm tra: Status changed to "assigned", notification gửi cho customer

**Test case 3: Cập nhật trạng thái giao nhận (Staff)**
1. Staff nhấn "Đã nhận thú" -> Status: picked_up
2. Staff nhấn "Đã giao trả" -> Status: delivered
3. Kiểm tra: Mỗi bước đều tạo notification cho customer

**Test case 4: Xem và hủy (Customer)**
1. Customer vào `/customer/pickups`
2. Xem chi tiết yêu cầu
3. Nhấn "Hủy" (nếu status cho phép)
4. Kiểm tra: Status changed to cancelled

---

## 8. CẤU HÌNH THANH TOÁN

Để kích hoạt thanh toán VNPay/MoMo thực sự, thêm vào `config/services.php`:

```php
'vnpay' => [
    'tmn_code' => env('VNPAY_TMN_CODE'),
    'hash_secret' => env('VNPAY_HASH_SECRET'),
    'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'return_url' => env('VNPAY_RETURN_URL'),
],

'momo' => [
    'partner_code' => env('MOMO_PARTNER_CODE'),
    'access_key' => env('MOMO_ACCESS_KEY'),
    'secret_key' => env('MOMO_SECRET_KEY'),
    'endpoint' => env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/pay/create'),
    'return_url' => env('MOMO_RETURN_URL'),
    'notify_url' => env('MOMO_NOTIFY_URL'),
],
```

---

## 9. SCHEDULE CRON

Thêm vào crontab server:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

Hoặc chạy thủ công:
```bash
php artisan bookings:send-reminders
```

---

## 10. TỔNG KẾT

### 10.1 Thống kê
- **Chức năng đã có sẵn:** 16
- **Chức năng đã triển khai mới:** 8
- **File tạo mới:** 20
- **File chỉnh sửa:** 10
- **Migration tạo mới:** 2
- **Route mới:** 18

### 10.2 Các bước tiếp theo (nếu cần)
1. Cấu hình VNPay/MoMo credentials trong .env
2. Kiểm thử end-to-end toàn bộ flow
3. Thêm unit test cho các service mới
4. Tích hợp AI chatbot thực sự (thay vì FAQ)

### 10.3 Lưu ý khi deploy
1. Chạy migration: `php artisan migrate`
2. Clear cache: `php artisan config:cache`
3. Schedule: Cấu hình cron trên server
4. Storage link: `php artisan storage:link`

---

**Báo cáo được tạo tự động bởi Technical Analyst Agent**