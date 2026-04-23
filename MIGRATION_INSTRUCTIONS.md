# 🚀 DATABASE MIGRATION GUIDE
## Hướng dẫn từng bước deploy database optimization

**Version**: 1.0  
**Date**: 2026-04-18  
**Total Migrations**: 17 files  
**Estimated Time**: 30-60 phút (tùy data size)

---

## ⚠️ TRƯỚC KHI BẮT ĐẦU

### 1. BACKUP DATABASE (BẮT BUỘC)

```bash
# Backup toàn bộ database
mysqldump -u root -p agile > backup_agile_pre_optimization_$(date +%Y%m%d_%H%M%S).sql

# Verify backup
ls -lh backup_agile_pre_optimization_*.sql
```

**Nếu dùng Laravel Forge/Vapor/other hosting**:
- Tạo snapshot từ control panel
- Export database qua phpMyAdmin

### 2. KIỂM TRA TRẠNG THÁI HIỆN TẠI

```bash
# Check current migrations
php artisan migrate:status

# Đảm bảo tất cả migrations cũ đã chạy
# Output nên là tất cả "Yes" nếu không có migration pending
```

### 3. TEST TRÊN STAGING (KHÁCH NGHIỆM)

Nếu có staging environment, **bắt buộc** test tại đây trước khi production.

---

## 📋 MIGRATION PLAN

### Phase 1: Non-Breaking Migrations (Safe - Có thể deploy riêng)

**Thời gian dự kiến**: 15-20 phút  
**Risk**: Thấp - chỉ thêm columns, indexes (no data loss)

```bash
# Enable maintenance mode (optional nhưng recommended)
php artisan down --render="errors::maintenance"

# Run migrations
php artisan migrate

# Hoặc chạy từng file nếu muốn kiểm soát:
php artisan migrate --path=database/migrations/2026_04_18_000001_refactor_permissions_table.php
php artisan migrate --path=database/migrations/2026_04_18_000002_add_breed_fk_and_soft_deletes.php
php artisan migrate --path=database/migrations/2026_04_18_000004_improve_bookings_table.php
php artisan migrate --path=database/migrations/2026_04_18_000005_create_service_categories_table.php
php artisan migrate --path=database/migrations/2026_04_18_000007_improve_payments_table.php
php artisan migrate --path=database/migrations/2026_04_18_000008_rename_notifications_table.php
php artisan migrate --path=database/migrations/2026_04_18_000009_create_audit_logs_table.php
php artisan migrate --path=database/migrations/2026_04_18_000010_create_promotion_uses_table.php
php artisan migrate --path=database/migrations/2026_04_18_000011_add_pickup_unique_constraint.php
php artisan migrate --path=database/migrations/2026_04_18_000012_add_review_constraints.php
php artisan migrate --path=database/migrations/2026_04_18_000013_add_additional_indexes.php
php artisan migrate --path=database/migrations/2026_04_18_000016_fix_foreign_keys_and_indexes.php
php artisan migrate --path=database/migrations/2026_04_18_000017_fix_remaining_issues.php
php artisan migrate --path=database/migrations/2026_04_18_000020_final_optimization.php
```

**Kiểm tra**:
```bash
php artisan migrate:status
# Tất cả nên là "Yes"
```

**Đợi 5-10 phút** để đảm bảo queries chạy ổn định.

---

### Phase 2: ENUM Conversion (Breaking - CẨN THẬN)

**Thời gian dự kiến**: 10-30 phút (tùy data size)  
**Risk**: Cao - Irreversible, có thể lock table

⚠️ **Migration 015 là IRREVERSIBLE** - không thể rollback, chỉ restore từ backup.

#### 2.1. Pre-Check (Bắt buộc)

```sql
-- Connect to MySQL
mysql -u root -p agile

-- Check distinct ENUM values (phải khớp với allowed values)
SELECT DISTINCT service_mode FROM bookings;
-- Expected: at_store, at_home

SELECT DISTINCT status FROM bookings;
-- Expected: pending, confirmed, processing, completed, cancelled

SELECT DISTINCT payment_status FROM bookings;
-- Expected: unpaid, paid, refunded, failed

SELECT DISTINCT payment_method FROM bookings;
-- Expected: cash, vnpay, momo, transfer

SELECT DISTINCT service_type FROM services;
-- Expected: grooming, vaccination, spa, checkup, surgery

SELECT DISTINCT gender FROM pets;
-- Expected: male, female, unknown

SELECT DISTINCT sender FROM messages;
-- Expected: user, staff, ai

SELECT DISTINCT discount_type FROM promotions;
-- Expected: percent, fixed

-- Nếu có giá trị nào ngoài danh sách → CẦN FIX TRƯỚC:
UPDATE bookings SET service_mode = 'at_store' WHERE service_mode NOT IN ('at_store','at_home');
-- Tương tự cho các cột khác
```

#### 2.2. Run ENUM Conversion

```bash
# Đảm bảo maintenance mode (đã bật từ phase 1)
php artisan migrate --path=database/migrations/2026_04_18_000015_convert_enums_to_varchar.php
```

**Monitor output**:
```bash
# Terminal khác: watch queries
mysql -u root -p -e "SHOW PROCESSLIST;" agile
```

**Nếu migration mất > 5 phút**:
- Với large tables (>100k), nên dùng `pt-online-schema-change`
- Hoặc chạy trong off-peak hours (night)

#### 2.3. Verify ENUM Conversion

```sql
-- Check column types đã chuyển thành VARCHAR
SELECT COLUMN_NAME, COLUMN_TYPE
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME IN ('bookings', 'services', 'pets', 'payments', 'messages', 'promotions')
  AND COLUMN_TYPE LIKE 'enum%';
-- Should return 0 rows

-- Check CHECK constraints tồn tại
SELECT CONSTRAINT_NAME, CHECK_CLAUSE
FROM information_schema.CHECK_CONSTRAINTS
WHERE CONSTRAINT_SCHEMA = DATABASE();
```

---

### Phase 3: Post-Migration

```bash
# Disable maintenance mode
php artisan up

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Rebuild caches (production)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload -o
```

---

## ✅ POST-DEPLOYMENT CHECKLIST

### Database Verification

```sql
-- 1. Check all indexes exist
SELECT TABLE_NAME, INDEX_NAME, COLUMN_NAME
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME IN ('bookings', 'payments', 'pets', 'services')
ORDER BY TABLE_NAME, INDEX_NAME;

-- 2. Check all foreign keys
SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = DATABASE()
  AND REFERENCED_TABLE_NAME IS NOT NULL;

-- 3. Check soft deletes columns exist
SELECT COLUMN_NAME FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND COLUMN_NAME = 'deleted_at'
  AND TABLE_NAME IN ('bookings', 'pets', 'services', 'reviews', 'messages', 'pet_categories', 'pet_breeds', 'payments', 'pet_medical_history', 'pet_progress_images');

-- 4. Check new tables exist
SHOW TABLES LIKE 'audit_logs';
SHOW TABLES LIKE 'promotion_uses';
SHOW TABLES LIKE 'permission_groups';
SHOW TABLES LIKE 'service_categories';
```

### Application Testing

**Critical flows to test**:

1. **Booking Flow**:
   - [ ] Customer tạo booking → success
   - [ ] Admin xem danh sách booking → nhanh
   - [ ] Admin assign staff → staff_id updated
   - [ ] Booking status thay đổi → logs created
   - [ ] Booking cancelled → cancelled_by, cancelled_reason filled

2. **Payment Flow**:
   - [ ] Payment tạo mới → gateway fields NULL (OK)
   - [ ] Payment với VNPay → gateway = 'vnpay', gateway_transaction_id filled
   - [ ] Booking completed → payment_status = 'paid'

3. **Pet Management**:
   - [ ] Customer thêm pet → success
   - [ ] Pet soft delete → deleted_at filled
   - [ ] Pet restore → deleted_at NULL

4. **Permissions**:
   - [ ] Admin có mọi quyền (role slug = 'admin')
   - [ ] Staff có booking.view permission
   - [ ] Customer không truy cập admin routes

5. **Notifications**:
   - [ ] Booking confirmed → UserNotification created
   - [ ] Notification mark as read → is_read = 1, read_at filled

6. **Soft Deletes**:
   - [ ] Soft deleted booking không hiển thị trong list (global scope)
   - [ ] Force delete booking → row removed
   - [ ] Deleted pet có thể restore

### Performance Monitoring

```bash
# Enable slow query log (nếu chưa)
mysql -u root -p -e "
  SET GLOBAL slow_query_log = 'ON';
  SET GLOBAL long_query_time = 2;
  SET GLOBAL slow_query_log_file = '/var/log/mysql/slow.log';
"

# Monitor trong 24h
tail -f /var/log/mysql/slow.log
```

**Expected**: Không có query > 1000ms với 100k+ records.

---

## 🆘 TROUBLESHOOTING

### Issue 1: Migration 015 (ENUM conversion) fails

**Error**: `Column type mismatch` or `Data truncated`

**Fix**:
```sql
-- Find invalid data
SELECT * FROM bookings WHERE service_mode NOT IN ('at_store','at_home','pickup');
SELECT * FROM services WHERE service_type NOT IN ('grooming','vaccination','spa','checkup','surgery','other');

-- Clean data
UPDATE bookings SET service_mode = 'at_store' WHERE service_mode NOT IN ('at_store','at_home','pickup');
UPDATE services SET service_type = 'other' WHERE service_type NOT IN ('grooming','vaccination','spa','checkup','surgery','other');

-- Retry migration
php artisan migrate:rollback --path=2026_04_18_000015_convert_enums_to_varchar.php
php artisan migrate --path=2026_04_18_000015_convert_enums_to_varchar.php
```

### Issue 2: Foreign key constraint fails

**Error**: `Cannot add foreign key constraint`

**Fix**:
```sql
-- Check orphaned records
SELECT p.* FROM pets p LEFT JOIN pet_breeds pb ON p.breed_id = pb.id WHERE pb.id IS NULL;

-- Option 1: Set breed_id = NULL
UPDATE pets SET breed_id = NULL WHERE id IN (orphan_ids);

-- Option 2: Create "Unknown" breed
INSERT INTO pet_breeds (category_id, name, slug) VALUES (1, 'Unknown', 'unknown');
UPDATE pets SET breed_id = (SELECT id FROM pet_breeds WHERE slug='unknown') WHERE id IN (orphan_ids);

-- Retry migration
```

### Issue 3: Duplicate entry for unique key

**Error**: `Duplicate entry 'X' for key 'uniq_booking_pickup'`

**Fix**: One booking has multiple pickup requests (data corruption)
```sql
-- Find duplicates
SELECT booking_id, COUNT(*) as count FROM pickup_requests GROUP BY booking_id HAVING count > 1;

-- Keep latest, delete others
DELETE pr1 FROM pickup_requests pr1
INNER JOIN pickup_requests pr2
WHERE pr1.booking_id = pr2.booking_id
  AND pr1.id < pr2.id;
```

### Issue 4: Table is locked (ENUM conversion)

**Error**: `Waiting for table metadata lock`

**Fix**:
```sql
-- Check locks
SELECT * FROM information_schema.innodb_locks;

-- Kill blocking process
SHOW PROCESSLIST;
KILL <process_id>;

-- Nếu vẫn lock, restart MySQL (last resort)
sudo service mysql restart
```

### Issue 5: Code breaks after Notification rename

**Error**: `Class 'App\Models\Notification' not found`

**Fix Option A (Alias - Quick)**:
File `app/Models/Notification.php` đã có alias → chỉ cần clear cache:
```bash
php artisan cache:clear
composer dump-autoload
```

**Fix Option B (Update code)**:
Find & replace:
```bash
grep -r "Notification::" app/ | grep -v UserNotification
# Update to UserNotification::
```

---

## 📊 ROLLBACK PLAN

### If Phase 1 fails (non-breaking):

```bash
# Rollback last batch
php artisan migrate:rollback

# Rollback specific migration
php artisan migrate:rollback --path=2026_04_18_000001_refactor_permissions_table.php

# Rollback all
php artisan migrate:reset
```

### If Phase 2 fails (ENUM conversion - IRREVERSIBLE):

⚠️ **Cannot rollback**. Must restore from backup:

```bash
# Drop all new migrations
php artisan migrate:reset

# Restore database
mysql -u root -p agile < backup_agile_pre_optimization_YYYYMMDD_HHMMSS.sql
```

---

## 🎯 SUCCESS METRICS

Sau khi deploy thành công, các metric nên cải thiện:

### Query Performance
- [ ] Booking list (user): < 100ms (trước 500ms)
- [ ] Staff schedule: < 50ms (trước 300ms)
- [ ] Admin reports: < 200ms (trước 1200ms)
- [ ] Pet medical history: < 20ms (trước 300ms)

### Database Health
- [ ] No orphaned FK records
- [ ] All indexes used (check `EXPLAIN` plans)
- [ ] No slow queries > 1s
- [ ] Disk usage tăng < 100MB

### Application Stability
- [ ] Không có error 500 liên quan DB
- [ ] Booking flow hoàn toàn
- [ ] Payment flow hoàn toàn
- [ ] Permission checks hoạt động

---

## 📞 EMERGENCY CONTACTS

**Nếu có vấn đề nghiêm trọng**:
1. **Rollback ngay lập tức** từ backup
2. **Check logs**: `tail -f storage/logs/laravel.log`
3. **Check MySQL error log**: `/var/log/mysql/error.log`
4. **Disable app** nếu cần: `php artisan down`

**Hotline team**:
- Database Admin: [số điện thoại/email]
- Backend Dev: [số điện thoại/email]

---

## 📝 MIGRATION CHECKLIST (Print & Check)

**Pre-Migration**:
- [ ] Database backed up
- [ ] Staging tested
- [ ] All devs notified
- [ ] Maintenance window scheduled
- [ ] Rollback plan documented

**During Migration**:
- [ ] Phase 1 completed without errors
- [ ] Wait 10 min, monitor logs
- [ ] Phase 2 (ENUM) started
- [ ] Monitor slow queries
- [ ] No deadlocks detected

**Post-Migration**:
- [ ] All migrations status = Yes
- [ ] Cache cleared
- [ ] Critical flows tested ✓
- [ ] Slow query log clean ✓
- [ ] App running normally ✓
- [ ] Team notified ✓

---

**Good luck! 🚀**

*Migration created by: Senior Backend Developer + Database Architect*
*Last updated: 2026-04-18*
