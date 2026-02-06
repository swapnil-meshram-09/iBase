# EduMaster Pro - Course Registration System

This is a complete web application for Course Registration and Management, built with React, Tailwind CSS, and Supabase.

## Features

### Part 1: Admin / Institute Module
- **Course Creation**: Form to add new courses with details like Title, Description, Dates, Duration, and Fees.
- **Course Listing**: View all created courses in a responsive table.
- **Student Registrations**: View a list of students who have registered for courses.

### Part 2: Student Module
- **Registration Form**: User-friendly form for students to sign up.
- **Dynamic Course Selection**: Automatically fetches available courses from the database.
- **Auto-fill Details**: Displays Course Duration and Amount based on selection.
- **Payment Integration (Demo)**: Generates a UPI-style QR Code for the course amount.
- **WhatsApp Integration**: Pre-fills a WhatsApp message with registration details for easy confirmation.

## Tech Stack
- **Frontend**: React, Tailwind CSS, Lucide React (Icons)
- **Backend/Database**: Supabase (PostgreSQL)
- **Utilities**: react-qr-code (for Payment QR)

## Setup Instructions

### 1. Database Setup
The application uses Supabase for data storage. You need to set up the database tables.

1.  Go to your Supabase Project Dashboard.
2.  Open the **SQL Editor**.
3.  Copy the content of the `schema.sql` file provided in this project.
4.  Paste it into the SQL Editor and click **Run**.
    - This will create the `courses` and `registrations` tables.

### 2. Environment Variables
The application connects to Supabase using environment variables.
Ensure your `.env` file (or environment configuration) contains:
- `SUPABASE_URL`
- `SUPABASE_ANON_KEY`

### 3. Running the Application
The application is pre-configured to run.
- Click **Admin Module** to add courses.
- Click **Student Registration** to test the student flow.

## Usage Guide

1.  **As an Admin**:
    - Go to "Admin Module".
    - Fill in the "Register New Course" form.
    - Click "View Courses" to see the list.

2.  **As a Student**:
    - Go to "Student Registration".
    - Fill in your details.
    - Select a course from the dropdown.
    - Click "Proceed to Payment".
    - Scan the QR code or click "Send Confirmation via WhatsApp".
