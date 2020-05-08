<p align="center"><img src="https://assets.website-files.com/5bea194a3705ec25b27ce94e/5bea1afbc107657eff26fb3d_Logo%20the%20agile%20monkeys.svg" width="200"></p>

## About MonkeyAPI
This is the API test for the second phase of The AgileMonkeys recruitment proccess developed by Atamán Vega Vega.

The objective is to create a REST API to manage customer data for a small shop. It will  work  as  the  backend  side  for  a  CRM  interface  that  is  being  developed  by  a different team. As the lead developer of the backend project, you'll be in charge of the API design and implementation.

It has been done in Laravel 7 PHP Framework with Vagrant and Homestead.

## Prerequisites
The Laravel framework has a few system requirements. All of these requirements are satisfied by the [Laravel Homestead](https://laravel.com/docs/7.x/homestead) virtual machine, so it's highly recommended that you use Homestead as your local Laravel development environment.

- PHP >= 7.2.5
- Composer
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## Installation steps
Follow the bellow steps to install and set up the application.

You can download the ZIP file or git clone from my repo into your project directory.

- In terminal go to your project directory and execute the following command
```
composer install
```
- Rename the .env.example file to .env file in the project root folder


**Option 1 - Using Vagrant and Homestead**

Laravel strives to make the entire PHP development experience delightful, including your local development environment. [Vagrant](https://www.vagrantup.com/) provides a simple, elegant way to manage and provision Virtual Machines.

- Download and install [Virtual Box's latest version](https://www.virtualbox.org/wiki/Downloads)

You can install another Virtual Machine. But I strongly recommended installing Virtual Box. In case you want to install other VM, please check the following [link](https://laravel.com/docs/7.x/homestead#first-steps) for more info.

- Download and install [Vagrant's latest version](https://www.vagrantup.com/downloads.html)

-  In the project directory generate the Homestead yaml file
```
php vendor/bin/homestead make
```
-  Modify the project URL in the file Homestead.yaml
```
sites:
    -
        map: <name-of-your-app>.test
```
- In case you don't have SSh Keys created, you have to execute the following command to create it
```
ssh-keygen -t rsa -b4096
```
- Add to your hosts file the url for the project
```
<ip-used-in-Homestead.yaml> <url-used-in-Homestead.yaml>
```
- Create the VM with the command
```
//The first time it is going to take a while, since it will get the homestead box
vagrant up
```
- When the VM is up and running, you can inside with the command
```
vagrant ssh
```
**Option 2 - In case you are not using Homestead**

- Edit the .env file and fill all required data for the bellow variables
```
APP_URL=http://localhost //your application domain URL go here

DB_HOST=127.0.0.1 // Your DB host IP. Here we are assumed it to be localhost
DB_PORT=3306 //Port if you are using except the default
DB_DATABASE=name_of_your_database
DB_USERNAME=db_user_name
DB_PASSWORD=db_password
```
- Make your storage and bootstrapp folder writable by your application user.

**Common Steps independently of the option taken**

 - Please note that in case of using Option 1 - Using Vagrant and Homestead, the commands have to be executed in project folder inside the VM.

- Edit the .env file and fill all required data regarding email service. Right now it is configures to use log service. So you could find the emails send in the file **storage/logs/laravel.log**. For more info about how to config mail services, please visit [Laravel Documentation](https://laravel.com/docs/7.x/mail#introduction)
```
MAIL_MAILER=log
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"
```
- To set the Application key run the bellow command in your terminal.
```
php artisan key:generate
```
- Create all the necessary tables need for the application by runing the bellow command.
```
  php artisan migrate
```
- Add the first admin user into the table to be able to start using the API
```
 php artisan db:seed
 // email:admin@example.com
 // password: secret
 // admin: 1
 // verified: 1
 ```
 - Generate Encryption keys
```
 php artisan passport:install
 ```
 - Add a client for oauth2 with grant_type = password. **Please write down the client_id and client_secret.**
```
 php artisan passport:client --password
 ```

## API endpoints and routes

**WEB routes**
-  GET  | "/" --> Access to the frontend to create oAuth2 clients via web

**API routes**

**Get user access tokens**
- POST  "oauth/token"
```
  {
  	"grant_type": "password",
  	"client_id": <Client ID we wrote down>, //required
	  "client_secret": <Client ID we wrote down>, //required
	  "username": <User email>, //required
	  "password": <User password>, //require
  }
```

**Customer** (Regular and admin users can manage customers if they are verified)
- POST  "customers"
```
  {
  	"name": <Customer first name>, //required
	  "surname": <Customer last name>, //required
	  "id": <Customer ID number>, //required
	  "photo": <Img file>,
  }
```
- GET  "customers"
- DELETE  "customers/{customer}"
- GET  "customers/{customer}"
- PUT / PATCH  "customers/{customer}"

**User**
- POST  "users" --> Just admin users can create other users
```
  {
  	"name": <User full name>, //required
	  "email": <User email>, //required and unique
	  "password": <User Password>, //required
	  "password_confirmation": <User password confirmation>, //Required an equal to password
  }
```
- GET  "users" --> Just admin users can get the user list
- DELETE  "users/{user}" --> Admin user can delete any other user, a regular user can delete itself
- GET  "users/{user}" --> Admin user can get any other user's info, a regular user can get your own info
- PUT / PATCH  "users/{user}" --> Admin user can update any other user's info, a regular user can update its own info
- PUT "users/changeUserStatus/{user}" --> Just admin users can change user status (admin or regular)
```
  {
  	"admin": 1 | 0, //required
  }
```

## Frontend version

The project comes with a frontend version to make easier to deal with oAuth2 client and access token managment. To use the frontend part just open your browser and navigate to the project URL.

Use the admin user credentials to log in
