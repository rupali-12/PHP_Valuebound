# User Registration and Management System

This project is a web application for managing user registration, login, and profile management. The application consists of multiple milestones that build upon each other to create a complete system.

## Milestones

### Milestone 1: Create a Registration Form

- **Objective**: Create a registration form with the following fields:
  - Firstname
  - Lastname
  - Email ID
  - Mobile Number
  - Password
  - Date of Birth (DOB)
- **Outcome**: Users can fill out the registration form with the required information.

### Milestone 2: Validate & Submit Form Data

- **Objective**: Validate the form data and submit it to the database.
- **Fields Saved in the Database**:
  - Firstname
  - Lastname
  - Mobile Number
  - Password
  - Date of Birth (DOB)
- **Outcome**: The user's information is validated and stored in the database.

### Milestone 3: Build the Login Form

- **Objective**: Create a login form with the following fields:
  - Email ID
  - Password
- **Outcome**: Users can log in using their credentials. Upon successful login, they are redirected to the page created in Milestone 4.

### Milestone 4: List of Registered Users

- **Objective**: Build a page that displays a list of all registered users in a tabular format.
- **Outcome**: Users can view all registered users in a table.

### Milestone 5: Edit/Delete Registered Users

- **Objective**: Provide options to edit or delete each user from the list.
- **Outcome**: Users can update or remove records from the user list.

### Milestone 6: User Profile Page

- **Objective**: Create a user profile page with the following features:
  - Display Firstname, Email ID, and Date of Birth.
  - Provide an option to upload an image.
  - Display the uploaded image along with the user's name.
- **Outcome**: Users can view and update their profile information, including their profile picture.

## Setup Instructions

### Prerequisites

- MySQL or any other relational database.
- Web server (e.g., Apache, Nginx) with PHP support.
- Browser (e.g., Chrome, Firefox).

### Database Setup

1. **Create the Database and Table**:
   Run the following SQL query to create the database and the necessary table:

   ```sql
   CREATE DATABASE vbphp;

   USE vbphp;

   CREATE TABLE users (
       id INT AUTO_INCREMENT PRIMARY KEY,
       firstname VARCHAR(50) NOT NULL,
       lastname VARCHAR(50) NOT NULL,
       email VARCHAR(100) NOT NULL UNIQUE,
       mobilenumber VARCHAR(15) NOT NULL UNIQUE,
       password VARCHAR(255) NOT NULL,
       dob DATE NOT NULL,
       image VARCHAR(255) DEFAULT NULL,
   );
   ```
