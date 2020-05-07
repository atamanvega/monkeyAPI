<p align="center"><img src="https://assets.website-files.com/5bea194a3705ec25b27ce94e/5bea1afbc107657eff26fb3d_Logo%20the%20agile%20monkeys.svg" width="200"></p>

## About MonkeyAPI
This is the API test for the second phase of The AgileMonkeys recruitment proccess developed by Atamán Vega Vega.

The objective is to create a REST API to manage customer data for a small shop. It will  work  as  the  backend  side  for  a  CRM  interface  that  is  being  developed  by  a different team. As the lead developer of the backend project, you'll be in charge of the API design and implementation.

It has been done in Laravel 7 PHP Framework with Vagrant and Homestead.

## Prerequisites
The Laravel framework has a few system requirements. All of these requirements are satisfied by the [Laravel Homestead](https://laravel.com/docs/7.x/homestead) virtual machine, so it's highly recommended that you use Homestead as your local Laravel development environment.

However, if you are not using Homestead, you will need to make sure your server meets the following requirements:

- PHP >= 7.2.5
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

**Clone the Application**

You can download the ZIP file or git clone from my repo into your project directory.

**Option 1 - In case you are not using Homestead**

- In terminal go to your project directory and Runtime
```
composer install
```

- Then copy the .env.example file to .env file in the project root folder

- Edit the .env file and fill all required data for the bellow variables
```
APP_URL=http://localhost //your application domain URL go here

DB_HOST=127.0.0.1 // Your DB host IP. Here we are assumed it to be localhost
DB_PORT=3306 //Port if you are using except the default
DB_DATABASE=name_of_your_database
DB_USERNAME=db_user_name
DB_PASSWORD=db_password
```

**Common Steps independently of the option taken**

 - Please note that in case of using Option 2 - Homestead, some of the commands have to be executed inside the VM. I will add a comment inside the code box in that cases

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
- Make your storage and bootstrapp folder writable by your application user.
- Create all the necessary tables need for the application by runing the bellow command.
```
  php artisan migrate
```
- Add the first admin user into the table to be able to start using the API
```
 php artisan db:seed
 // email:admin
 ```

 - Fill default data if your need by running bellow command.
```
 php artisan db:seed
 ```

