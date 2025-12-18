-- تحديث enum payment_status لإضافة القيم الجديدة
ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('pending', 'paid', 'failed', 'refunded', 'pending_manual') DEFAULT 'pending';
