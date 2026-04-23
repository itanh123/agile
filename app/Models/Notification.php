<?php

/**
 * Notification Alias - Backward Compatibility
 *
 * File này tạo alias để code cũ vẫn chạy được với model mới.
 * Sau khi migrate xong, bạn có thể:
 * 1. Dùng UserNotification trực tiếp (recommended)
 * 2. Hoặc giữ alias này để không phải sửa code cũ
 *
 * Cách dùng:
 * - Old: Notification::send(...)
 * - New: UserNotification::send(...)
 * - Both work với alias này.
 */

if (!class_exists('Notification')) {
    class_alias(\App\Models\UserNotification::class, 'Notification');
}

// Đảm bảo Notification facade/alias hoạt động
if (!function_exists('notification')) {
    function notification()
    {
        return \App\Models\UserNotification::class;
    }
}
