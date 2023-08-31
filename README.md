# House Advertisement Web Application

This is a fullstack web application for house advertisements, developed using PHP Yii2 framework. Admin users can post advertisements, and the application includes push notification functionality.

## Table of Contents

- [Overview](#overview)
- [Installation](#installation)
- [Usage](#usage)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [License](#license)

## Overview

This web application allows admin users to post and manage house advertisements. Users can view available houses for rent or sale. The application also includes push notification functionality to keep users updated about new advertisements.

## Installation

To run this application locally, follow these steps:

1. Clone the repository: `git clone https://github.com/Miladkiani/housead-web-application-yii2.git`
2. Navigate to the project directory: `cd housead-web-application-yii2`
3. Install dependencies: `composer install`
4. Configure the database connection in `config/db.php`
5. Apply migrations: `php yii migrate`

## Usage

After installing and configuring the application, you can run it using the Yii development server:

```bash
php yii serve
```

Access the application in your web browser at `http://localhost:8080`.

## Features

- Admin users can post and manage house advertisements
- Users can view and search for houses based on criteria
- Push notification functionality to notify users about new advertisements
- Responsive user interface for seamless browsing on various devices

## Technologies Used

- PHP Yii2 framework
- MySQL database
- HTML, CSS, JavaScript

## License

This project is licensed under the [MIT License](LICENSE).
