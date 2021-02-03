# SlimReact CRUD web app

This project is intended to explore an integration between Slim4 and a compiled React app.

## Requirements

* MySQL 8
* PHP ^7.2
* [Composer](https://getcomposer.org/) in your global path
* Node.js/NPM LTS

## Main description

The project is composed of two main parts:

* **backend**: this is a [Slim 4](https://www.slimframework.com/) REST microservice that exposes all the necessary routes for the CRUD operations on departments and employees. It has also a couple of routes for displaying the requested reports (implemented as views in MySQL)
* **frontend**: this is a stub of a classic React app written with Typescript and created from [CRA](https://create-react-app.dev/). It uses Bootstrap 4.5 as CSS framework, bundled with [react-bootstrap](https://react-bootstrap.netlify.app/): this is a full-rewrite of Bootstrap's components in React, avoiding the jQuery and Popper dependencies (they manipulate the DOM, which would conflict with React).

## Setup

### Backend

First of all, you need to create the database. There's already a script for that. Open a terminal in the root of this project and do the following (please provide the MySQL root password when prompted):

```bash
cd api/scripts
chmod a+x db_reset.sh
./db_reset.sh
```

If you're using Windows, open your MySQL client of choice and execute the script `setup.sql`.  
The script will create the required database, tables and records for the tests.  
Now create an `.env` file inside `api` folder, following the `env.example` template.  
Once you've done with MySQL, let's install all the PHP dependencies. Open a terminal in the root of this project and do the following:

```bash
cd api
composer install
composer run start
```

This will start the Slim app using the [PHP built-in server](https://www.php.net/manual/en/features.commandline.webserver.php).

### Tests

Just do `composer run test`. Every time a test is executed, the database gets reset to its original state, unless there's a dependency between the tests themself.

### Frontend

Open a terminal in the root of this project and do the following:

```bash
cd ui
npm install
npm start
```

This will start the development server, allowing you to edit the code and immediately seeing the result in the browser without refreshing the page.  
The tests can be done with `npm test`, even though they're not implemented here.  

## Deployment

A possible structure of the deployed app could be the following:

```
ubuntu@ip-172-31-43-146:/var/www/crudapp$ ll
total 68
drwxr-xr-x  6 ubuntu ubuntu 4096 Jan  6 18:00 ./
drwxr-xr-x  4 root   root   4096 Feb  3 08:34 ../
-rw-rw-r--  1 ubuntu ubuntu  166 Jan  6 18:00 .htaccess
drwxrwxr-x  9 ubuntu ubuntu 4096 Jan  6 17:35 api/
-rw-rw-r--  1 ubuntu ubuntu  833 Jan 15 08:26 asset-manifest.json
drwxrwxr-x  2 ubuntu ubuntu 4096 Jan  6 17:33 css/
-rw-rw-r--  1 ubuntu ubuntu 3870 Jan 15 08:26 favicon.ico
drwxrwxr-x 10 ubuntu ubuntu 4096 Jan  6 17:33 fontawesome-free-5.15.1-web/
-rw-rw-r--  1 ubuntu ubuntu 3391 Jan 15 08:33 index.html
-rw-rw-r--  1 ubuntu ubuntu 5347 Jan 15 08:33 logo192.png
-rw-rw-r--  1 ubuntu ubuntu 9664 Jan 15 08:33 logo512.png
-rw-rw-r--  1 ubuntu ubuntu  492 Jan 15 08:33 manifest.json
-rw-rw-r--  1 ubuntu ubuntu   67 Jan 15 08:33 robots.txt
drwxrwxr-x  3 ubuntu ubuntu 4096 Jan  6 17:33 static/
```

Steps:

- put the compiled react app in the root of the project
- put an `.htaccess` file which redirects all the traffic to `/`

```
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-l
RewriteRule ^.*$ / [L,QSA]
```

- put the Slim app inside `api` folder
- bootstrap your Slim app inside `api/index.php`, removing the `public` dir in that directory.
- create a virtual host specifying the document root. This is my example:

```xml
<VirtualHost *:80>
    ServerName crudwebapp.lan
    DocumentRoot /var/www/crudwebapp

    ErrorLog /var/log/apache2/crudwebapp_error.log
    CustomLog /var/log/apache2/crudwebapp_access.log combined

    <IfModule mpm_itk_module>
        AssignUserId ccastelli ccastelli
    </IfModule>
    <Directory /var/www/crudwebapp>
        Options -Indexes
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```
