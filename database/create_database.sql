-- إنشاء قاعدة البيانات لمشروع Askila
-- قم بتشغيل هذا الملف في MySQL

-- إنشاء قاعدة البيانات
CREATE DATABASE IF NOT EXISTS askila_database 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- استخدام قاعدة البيانات
USE askila_database;

-- إنشاء مستخدم جديد (اختياري)
-- CREATE USER IF NOT EXISTS 'askila_user'@'localhost' IDENTIFIED BY 'askila_password';
-- GRANT ALL PRIVILEGES ON askila_database.* TO 'askila_user'@'localhost';
-- FLUSH PRIVILEGES;

-- عرض رسالة نجاح
SELECT 'Database askila_database created successfully!' as message;
