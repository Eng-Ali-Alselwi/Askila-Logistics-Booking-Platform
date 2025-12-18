# Askila - Logistics & Flight Booking Management System 🚀

**Askila** is a comprehensive, enterprise-grade web application built with **Laravel 12**. It is designed to provide seamless management solutions for logistics companies and travel agencies. The system integrates advanced shipment tracking, flight booking workflows, multi-channel notifications, and secure payment processing into a single, unified platform.

## 🌟 Key Features

### 📦 Logistics & Shipment Management
*   **End-to-End Tracking:** Real-time shipment tracking with status history.
*   **Shipment Lifecycle:** Full control over shipment creation, processing, delivery, and archiving.
*   **Branch Management:** Manage multiple branches and assign shipments based on location.
*   **Automated Status Updates:** Auto-update logic for shipment stages.

### ✈️ Flight Booking System
*   **Flight Search & Reservation:** Advanced search engine for flights.
*   **Booking Workflow:** Seamless booking process from selection to ticket issuance.
*   **Payment Integration:** Secure online payments via **Stripe** and **PayPal**.
*   **Ticket Management:** Issue, cancel, and refund tickets effortlessly.

### 🛡️ Admin & Security
*   **Role-Based Access Control (RBAC):** Granular permissions using `spatie/laravel-permission` (Admin, Manager, Agent, User).
*   **Secure Authentication:** Multi-guard authentication systems for Admins and Customers.
*   **Activity Logging:** Detailed logs for critical actions.

### 🔔 Notifications & Localization
*   **SMS Gateway Integration:** Integrated with MoraSMS for transactional messages.
*   **Email Notifications:** Automated emails for booking confirmations and updates.
*   **Multi-Language Support:** Designed to support localization (AR/EN).

## 🛠️ Technology Stack
*   **Backend Framework:** Laravel 12.x
*   **Frontend:** Blade Templates, Livewire for dynamic interactions.
*   **Database:** MySQL.
*   **Payment Gateways:** Stripe API, PayPal SDK.
*   **Permissions:** Spatie Permission Package.
*   **Utilities:** Barryvdh DOMPDF (for invoices), Maatwebsite Excel.

---

## ⚙️ Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/askila.git
   cd askila
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   - Create a MySQL database.
   - Update DB credentials in `.env`.
   - Run migrations and seeders:
     ```bash
     php artisan migrate --seed
     ```

5. **Serve Application**
   ```bash
   php artisan serve
   ```
