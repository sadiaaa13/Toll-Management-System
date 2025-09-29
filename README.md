IoT Toll Management System
An Android + PHP + MySQL based Toll Management System with RFID authentication.
Users register, get approved by an admin, and can manage their toll balance, recharge, and view vehicle details via a mobile app.

Features

User (Android app)

Register and login using email/phone/vehicle number
RFID-based authentication
View profile (name, vehicle, RFID, balance)
Recharge toll balance
Logout

Admin (Website)

Monitor the whole system
Handle emergency users and approvals
Handle recharge
Approve pending users through email
Assign RFID numbers
Manage verified users
Monitor transactions

Hardware 
Connect ESP32 through API with a website using Arduino IDE 
Tech Stack

Frontend (Mobile + website): Android (Java, XML, Retrofit), HTML
Backend: PHP (API endpoints)
Database: MySQL

Networking: Retrofit (API calls to server)
