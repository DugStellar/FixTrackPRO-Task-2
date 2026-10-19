# FixTrack PRO - XISD6329 Portfolio of Evidence (POE)

## 📌 Project Overview
**FixTrack PRO** is a hybrid mobile and web-based maintenance and incident tracking system. It enables users and staff to log, track, and manage maintenance reports, view live dashboards, and interact with a secure backend API connected to a relational database.

---

## 🔑 Default Test Credentials

Use the following pre-configured credentials to log into the application and test different access levels:

| Role | Username / Email | Password | Access Level |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@fixtrack.com` | `Admin123!` | Full admin dashboard access & report management |
| **Standard User** | `user@fixtrack.com` | `User123!` | Report creation, profile management & ticket tracking |

*(Note: Additional test accounts can be registered directly through the Android app or manually added to the `users` table in `fixtrack_db.sql`.)*

---

## 🛠️ Tech Stack & Architecture
* **Mobile Frontend:** Android (Kotlin, Android Studio, Volley Library)
* **Backend API:** PHP (RESTful architecture)
* **Database Management:** MySQL (Relational database managed via XAMPP / phpMyAdmin)
* **Version Control:** Git, GitHub, and Azure DevOps

---

## 📁 Repository Structure
```text
CMCSPOE/
│
├── AndroidApp/                     <-- Complete Android Studio Project Source Code
│   ├── app/                        <-- Source code, layouts, and NetworkConfig.kt
│   ├── build.gradle                <-- Project build configuration
│   └── settings.gradle             <-- Module settings
│
├── htdocs_api/                     <-- PHP Backend API Scripts
│   ├── admin_dashboard.php         <-- Administrator control panel logic
│   ├── dashboard.php               <-- User dashboard data endpoints
│   ├── db.php                      <-- Database connection configuration
│   └── login.php                   <-- Authentication handler
│
├── fixtrack_db.sql                 <-- Exported MySQL Database Schema & Data
└── README.md                       <-- Project Documentation & Setup Guide
