# Task Management App
## Introduction
Task Management App is a demo inventory management application developed as a programming assessment. It showcases proficiency in modern web development practices and tools using Laravel, Laravel Breeze for authentication, and Vue.js for the user interface.

## Prerequisites
Before you begin, ensure you have met the following requirements:
- PHP >= 8.0
- Composer
- Node.js and npm
## Features

*   User authentication (registration and login)
*   Creating tasks with a title and description
*   Marking tasks as completed
*   Deleting tasks
*   Filtering tasks by status (completed or not)

## Installation Instructions
### Step 1: Clone the repository
```bash
git clone <repository-url>
```

### Step 1: Copy .env.example to .env
Copy the .env.example file to create your .env file:
```bash
cp .env.example .env
```

### Step 2: Install Composer Dependencies
Install the project dependencies using Composer:
```bash
composer install
```

### Step 3: Install Composer Dependencies
Install the necessary NPM packages:
```bash
npm install
```

### Step 4: Run Migrations and Seed Database
Run the database migrations and seed the database with initial data:
```bash
php artisan migrate --seed
```

### Step 5: Start the Development Server
Start the Laravel development server:
```bash
php artisan serve
```

### Step 6: Compile Assets
Compile the front-end assets:
```bash
npm run dev
```
## Usage

1.  Register a new user or log in with the default credentials (test@example.com / de!etTh1s)
2.  Create new tasks by providing a title and description.
3.  Mark tasks as completed by clicking the "Complete" button next to them. This will move them to the "Completed Tasks" tab.
4.  Delete tasks by clicking the "Delete" button next to them.
5.  You can reset the task status by clicking the "Reset" button. This will move the task back to the "Current Tasks" tab.

## Running Tests

1. Run the tests: `php artisan test`
2. **Note:** Running tests will reset the database. Remember to re-seed the database afterward with: `php artisan db:seed`

## Future Improvements

*   Adding more advanced filtering and sorting options
*   Implementing task assignment to different users
*   Adding the ability to edit existing tasks
*   Improving the user interface with a more modern design

