# Disaster Management System Project Report

## 1. Project Overview

The AI-Based Disaster Management and Weather Forecasting System is a web-based application designed to support disaster preparedness, awareness, and emergency response. The system allows users to register, view disaster alerts, submit incident reports, access weather forecasts, receive notifications, and locate emergency contacts and shelter centers.

## 2. Technology Stack

- Frontend: HTML, CSS, JavaScript
- Backend: PHP
- Database: MySQL
- Server: XAMPP Apache
- External API: Open-Meteo or weather forecast API

## 3. Main Features

### User Features
- User registration and login
- Dashboard with alert, report, and notification summaries
- View active disaster alerts
- Submit disaster reports with optional image upload
- View personal report status
- Receive notifications for disaster alerts
- View emergency contacts and shelter centers
- Access weather forecast and AI-based disaster risk prediction
- Update profile and change password

### Admin Features
- Admin login and protected admin panel
- Add and manage disaster alerts
- Manage emergency contacts
- Add and manage shelter centers
- Manage registered users
- Manage disaster reports (verify/reject)
- View analytics dashboard with totals and recent items
- Admin profile and password management

## 4. Application Modules and Key Pages

### Public / User Modules
- `index.php` — Homepage with summary cards and recent data
- `login.php` — User login
- `register.php` — User registration
- `dashboard.php` — User dashboard
- `report-disaster.php` — File upload and report submission
- `my-reports.php` — User report history
- `notifications.php` — User notifications
- `weather.php` — Weather data retrieval and display
- `forecast.php` — 10-day weather forecast and risk analysis
- `ai-prediction.php` — Disaster risk prediction based on user weather input
- `alerts.php` — Disaster alert browser
- `emergency.php` — Emergency contacts listing
- `shelters.php` — Shelter center listing
- `profile.php` — User profile management
- `change-password.php` — Change user password
- `about.php` — Project overview and features
- `contact.php` — Contact and support information

### Admin Modules
- `admin/admin-login.php` — Admin login page
- `admin/dashboard.php` — Admin analytics dashboard
- `admin/add-alert.php` — Add new disaster alerts
- `admin/manage-alerts.php` — Manage disaster alerts
- `admin/add-contact.php` — Add emergency contact entries
- `admin/manage-contacts.php` — Manage emergency contacts
- `admin/add-shelter.php` — Add shelter center entries
- `admin/manage-shelters.php` — Manage shelter centers
- `admin/manage-users.php` — Manage registered users
- `admin/manage-reports.php` — Manage and verify user reports
- `admin/admin-profile.php` — Admin profile page
- `admin/admin-change-password.php` — Admin password change
- `admin/admin-logout.php` — Admin logout

## 5. Data Model Summary

The system uses a relational MySQL database with the following main tables.

### `users`
- `user_id` INT AUTO_INCREMENT PRIMARY KEY
- `full_name`, `email`, `phone`, `password`, `address`
- `role` ENUM('user','volunteer')
- `status` ENUM('active','blocked')
- `created_at` TIMESTAMP

### `admins`
- `admin_id` INT AUTO_INCREMENT PRIMARY KEY
- `name`, `email`, `password`
- `role` ENUM('super_admin','admin')
- `created_at` TIMESTAMP

### `disaster_alerts`
- `alert_id` INT AUTO_INCREMENT PRIMARY KEY
- `title`, `alert_type`, `location`, `severity`, `description`, `instruction`
- `status` ENUM('Active','Inactive','Expired')
- `issued_at` TIMESTAMP

### `weather_data`
- `weather_id` INT AUTO_INCREMENT PRIMARY KEY
- `location`, `temperature`, `humidity`, `wind_speed`, `pressure`, `rainfall`, `weather_condition`
- `recorded_at` TIMESTAMP

### `forecast_data`
- `forecast_id` INT AUTO_INCREMENT PRIMARY KEY
- `location`, `forecast_date`, `max_temp`, `min_temp`, `rain_chance`, `wind_speed`, `condition_text`, `risk_level`
- `created_at` TIMESTAMP

### `ai_predictions`
- `prediction_id` INT AUTO_INCREMENT PRIMARY KEY
- `location`, `temperature`, `humidity`, `rainfall`, `wind_speed`, `pressure`
- `predicted_disaster`, `risk_percentage`, `risk_level`
- `prediction_date` TIMESTAMP

### `disaster_reports`
- `report_id` INT AUTO_INCREMENT PRIMARY KEY
- `user_id` INT
- `disaster_type`, `location`, `description`, `image`, `status`, `admin_note`
- `reported_at` TIMESTAMP
- `user_id` foreign key references `users(user_id)`

### `notifications`
- `notification_id` INT AUTO_INCREMENT PRIMARY KEY
- `user_id` INT
- `title`, `message`, `type`, `is_read`
- `created_at` TIMESTAMP
- `user_id` foreign key references `users(user_id)`

### `emergency_contacts`
- `contact_id` INT AUTO_INCREMENT PRIMARY KEY
- `service_name`, `service_type`, `phone`, `email`, `location`, `description`, `status`
- `created_at` TIMESTAMP

### `shelter_centers`
- `shelter_id` INT AUTO_INCREMENT PRIMARY KEY
- `shelter_name`, `location`, `capacity`, `available_space`, `contact_person`, `phone`, `facilities`, `status`
- `created_at` TIMESTAMP

### `contact_messages`
- `message_id` INT AUTO_INCREMENT PRIMARY KEY
- `name`, `email`, `subject`, `message`, `status`
- `created_at` TIMESTAMP

## 6. Database and Connection

- Database name: `disaster_management_db`
- Connection file: `config/db.php`
- The database connection uses MySQL credentials and port configured in `config/db.php`.
- Example tables are defined in `database/final_database.sql`.
- There is also an ER and procedure report file at `database/ER_and_Procedure_Report.md`.

## 7. System Workflow

### User workflow
1. User registers via `register.php`.
2. User logs in via `login.php`.
3. User views dashboard and active alerts.
4. User submits disaster reports from `report-disaster.php`.
5. User checks notifications and report status.
6. User views weather forecast and AI risk predictions.

### Admin workflow
1. Admin logs in via `admin/admin-login.php`.
2. Admin uses `admin/dashboard.php` to review analytics.
3. Admin creates alerts using `admin/add-alert.php`.
4. Admin manages contacts, shelters, users, and reports.
5. Admin updates profile or changes password.

## 8. Important Files and Directories

- `config/db.php` — database connection configuration
- `includes/header.php`, `includes/footer.php` — shared page layout
- `includes/functions.php` — helper functions
- `includes/districts.php` — district dropdown list
- `assets/css/style.css` — styling
- `assets/js/main.js` — frontend scripts
- `assets/uploads/` — user-uploaded report images
- `database/` — SQL dumps and schema documentation
- `admin/` — admin panel pages

## 9. Installation Notes

1. Place the project folder in the web server root (e.g., `xampp/htdocs`).
2. Import `database/final_database.sql` into MySQL.
3. Configure database credentials in `config/db.php`.
4. Start Apache and MySQL services.
5. Open the site in a browser and test login/register flows.

## 10. Recommended Improvements

- Add stronger input validation and prepared statements to prevent SQL injection.
- Improve password and session security.
- Add role-based access control and better admin authorization.
- Add support for real-time alert push notifications.
- Enhance AI prediction logic with real machine learning models.
- Add responsive mobile design improvements.
- Add more detailed logging and error reporting.

## 11. Conclusion

This disaster management system is a complete PHP/MySQL application with both user and admin functionality. It combines weather forecasting, AI-based risk prediction, alert management, report submission, notifications, emergency resources, and a backend management panel.

---

### References
- `database/final_database.sql`
- `database/ER_and_Procedure_Report.md`
- `about.php`
- `admin/dashboard.php`
- `ai-prediction.php`
- `forecast.php`
- `report-disaster.php`
