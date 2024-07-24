# CSI 3140 Assignment 4

This respository is for CSI 3140 Assignment 4

## Student Info

Allen Mei 300238743
Patrick Meyer 300220498

## Overview

The Hospital Triage application is designed to streamline the management of emergency room wait times. It provides a system for triage staff and patients to interact with and understand wait times based on two key dimensions: the severity of injuries and the length of time already in the queue. 

**Features:**
- **Administrator Functionality:** Allows staff to sign in, view a comprehensive list of patients, add new patients, and mark patients as treated, removing them from the wait queue.
- **Patient Functionality:** Enables patients to sign in using their name and a 3-letter code to check their approximate wait time, which updates dynamically as patients are treated.

## Features

### For Administrators
- **Sign In:** Admins can securely sign in to access the administrative panel.
- **Add Patient:** Administrators can add new patients to the system by entering their name, a 3-letter code, and the severity of their condition.
- **View Patients:** The admin panel displays a full list of patients, including those waiting and those who have been treated.
- **Mark as Treated:** Admins can select patients who have been treated to update their status and remove them from the queue.

### For Patients
- **Sign In:** Patients can log in using their name and a 3-letter code.
- **Check Wait Time:** Once logged in, patients can view their approximate wait time in the queue. This information is updated as other patients are treated.

## How to Use

### Administrator
1. **Sign In:** Click the "Admin Sign In" button and enter your username and password.
2. **Add Patient:** Use the form provided to input patient details and add them to the system.
3. **View Patients:** Check the list of patients to monitor wait times and patient status.
4. **Mark as Treated:** Select patients who have been treated to update their status and remove them from the queue.

### Patient
1. **Sign In:** Click the "User Sign In" button and enter your name and 3-letter code.
2. **Check Wait Time:** View your estimated wait time on the user section of the application.


## How To Run

```cd public, php -S localhost:8000```

## User Interface States



[Database Design](/docs/db.md),
[Database Schema](/db/schema.sql), and
[Sample Data (SQL)](/db/seed.sql).

Open command prompt

Windows
setx DB_USERNAME "your_username"
setx DB_PASSWORD "your_password"

Linux/macOS
export DB_USERNAME="your_username"
export DB_PASSWORD="your_password"
source ~/.bashrc


