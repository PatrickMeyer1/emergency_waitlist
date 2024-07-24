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
- **Estimated Wait Time Calculation:** Every patient has a healing time of 10 minutes. Patients with more severe injuries will be passed before those with less severe injuries. Same severity is whoever came first. This is only an estimation and admins need to mark the patient as treated (whether it is faster or slower than 10 minutes).

## How to Use

### Administrator

1. **Sign In:** Click the "Admin Sign In" button and enter your username (admin01/admin02) and password (adminpassword01, adminpassword02).
2. **Add Patient:** Use the form provided to input patient details and add them to the system.
3. **View Patients:** Check the list of patients to monitor wait times and patient status.
4. **Mark as Treated:** Select patients who have been treated to update their status and remove them from the queue.

### Patient

1. **Sign In:** Click the "User Sign In" button and enter your name and 3-letter code. Sample: Name-'Alice Johnson', Code-'A01'
2. **Check Wait Time:** View your estimated wait time on the user section of the application.

## How To Run

`cd public, php -S localhost:8000`

## How to Setup Local Database

1. Go to MySQL and create a new MySQL connections, and name it hospital_db. Select localhost as hostname, input your username and password
2. Enter connection and create a new schema, name it hospital_db, click apply, and apply again
3. Now execute the queries in [Database Schema](/db/schema.sql) to create the tables
4. Now execute the queries in [Sample Data (SQL)](/db/seed.sql) to populate the tables initially
5. Fill in the config.php with your username and password
6. Make sure the database is active and running by having it open
7. Contact us if it does not work, we both are using it successfully

This is using a built-in PHP server

## Handling Errors

### Uncaught Error: Class "mysqli" not found:

1. Go to to your php download folder in your C:/ drive, change the name of php.ini-development/production to php.ini
2. Access the file and change the value of `;extension=mysqli` to `extension=mysqli`
3. `CTRL+F` to find `; On windows: extension_dir`
4. Change it to `C:\PHP\ext`
5. Contact us if it does not work, we both are using it successfully

## Database Documentation

[Database Design](/docs/db.md),
[Database Schema](/db/schema.txt), and
[Sample Data (SQL)](/db/seed.txt).
