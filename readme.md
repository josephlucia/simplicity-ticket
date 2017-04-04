# Simplicity Ticket System

Simplicity Ticket System is open source trouble ticket database for your organization.

Its allure is in its simplicity with regards to setup, features, and functionality. It was created to fill a need at my organization and to replace a $5,000 a year ticket system that was so complex to use, that it wasn't used. 

## Simple Features

- Built completely using open source frameworks. Laravel 5.4, Vue.js and Twitter Bootstrap.
- Users can register themselves and create a ticket in less than a minute.
- Users can view the details of the tickets, including comments, that they can contribute too also.
- Staff have the ability to add comments but keep them hidden from the ticket creator by checking a box before submitting.
- Staff can transfer tickets between each other changing the assigned-to value to available staff.
- Staff can close a ticket or re-open a closed ticket at any time.

## Simple Settings

- Set the application name to anything you like for branding purposes.
- Set an alias for Department if you don't have departments. Examples are Building, School, Entity, etc.
- Add as many "departments" (or whatever alias you decide) as you like for your users to choose from when creating a ticket.
- Admin user(s) can add/lock/unlock/delete "staff", these are your support technicians.
- Admin user(s) can assign staff to as many "departments" as needed so they see more open tickets.
- Domain filtering acts as a whitelist or blacklist to control who can register to use the application.
- Bulk transfer allows you to transfer all tickets between staff or departments.
- Registered users can be locked/deleted to remove access if needed.

## Requirements

- Same requirements needed for Laravel 5.4 documented at [https://laravel.com/docs/5.4](https://laravel.com/docs/5.4).
- HTTP server with PHP support (eg: Apache, Nginx, Caddy)
- A supported database: MySQL, PostgreSQL or SQLite
- [Node.js](https://nodejs.org/en/) for running the **npm run production** command.

## Installation

1. Get the source code
- Github: Download the ZIP
- Git: git clone git://github.com/josephlucia/simplicity-ticket.git
2. Open the terminal of your web server, navigate to simplicity-ticket and run the following commands:
- composer install --no-dev -o
- npm install
- npm run production
- cp .env-example .env
- php artisan key:generate
3. Open the .env file and enter your database details for the flavor of SQL you have access to.
4. Edit the Mail server details also. It will be required to send emails from the system.
5. Go back to the terminal, and run **php artisan migrate**.
6. Open the browser and navigate to your installed application.
7. Complete the setup of the admin account.
8. Continue setting up any other settings necessary to your organization.
9. Begin using the application.

## License
Copyright 2017 Joseph Lucia

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
