# HR & OE Management

OOP Project for HR & OE Management
Using PHP & Bootstrap

## Project Overview

This project is designed to manage Human Resources (HR) and Organizational Entities (OE) using Object-Oriented Programming (OOP) principles. It includes various models representing different entities within an organization and provides CRUD (Create, Read, Update, Delete) operations for managing these entities.

## Features

- **CRUD Operations**: Available for Employees, Customers, Countries, and Regions.
- **Login System**: Secure login system to manage user sessions.
- **Faker Integration**: Utilizes the Faker library to generate fake data for testing purposes.
- **Responsive Design**: User interface designed to be responsive and user-friendly.
- **DataTables Integration**: Uses DataTables for enhanced table functionalities like sorting, searching, and pagination.

## Models with CRUD Operations

- **Employee**: Manage employee details including personal information, job details, and salary.
- **Customer**: Manage customer information and interactions.
- **Country**: Manage country details.
- **Region**: Manage region details.

## How It Works

1. **Login System**: Users must log in to access the system. The login system ensures secure access to the application.
2. **CRUD Operations**: Users can perform CRUD operations on the available models. Each model has its own set of forms and views for managing data.
3. **Faker Integration**: Faker is used to generate fake data for testing. This helps in populating the database with sample data without manual entry.
4. **Responsive Design**: The application is designed to work seamlessly on various devices, ensuring a good user experience.

## Getting Started

1. **Clone the Repository**: Clone the project repository to your local machine.
2. **Install Dependencies**: Use Composer to install the required dependencies.
   ```bash
   composer install
3. **First time setup**: An admin user will be created for the first time setup with the same password as the username.
