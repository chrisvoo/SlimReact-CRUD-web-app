# Chessable assignment

## Requirements

* MySQL 8
* PHP ^7.2
* [Composer](https://getcomposer.org/) in your global path
* Node.js/NPM LTS

## Main description

The assignment is composed of two main parts:

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

* create a virtual host specifying the document root. This is my example:

```xml
<VirtualHost *:80>
    ServerName chessable.lan
    DocumentRoot /var/www/chessable

    ErrorLog /var/log/apache2/chess_error.log
    CustomLog /var/log/apache2/chess_access.log combined

    <IfModule mpm_itk_module>
        AssignUserId ccastelli ccastelli
    </IfModule>
    <Directory /var/www/chessable>
        Options -Indexes
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

* run `deploy.sh` script changeding the values accordingly. This will sync and copy the required files in `/var/www/chessable`. Basically the build of the frontend will be in the root, while `api` will contain the Slim app.
* create an `.htaccess` file which redirect every request to `index.php` file:

```text
<IfModule mod_rewrite.c>
  RewriteEngine On
  # Everything must be redirected to index, so that even
  # if a user points the browser to an
  # existing file, he will get a 404
  RewriteRule ^.*$ index.php [QSA,L]
</IfModule>
```

If you're using an invented name like `chessable.lan`, please edit your `hosts` file, inserting a record which points to `127.0.0.1`.

## Possible imporvements

### Backend proposals

There's no authentication/authorization system in place. A possible solution could be a classic role-based authroization system and a session-based authentication, all relying on MySQL. Currently, the backend is just a stateless microservice.

### Frontend proposals

The frontend is not complete of course. it's a subset of all the CRUD operations, fully implemented inside the backend. In reality it's often needed a global state management mechanism, usually provided by [React Context](https://reactjs.org/docs/context.html) or third-party modules like [Redux](https://redux.js.org/).  
For the integration tests, a nice way could be using [Puppeteer](https://pptr.dev/) or [Cypress](https://www.cypress.io/). They both run a browser that can be instructed to perform all the necessary operations.

### Deployment proposals

The bash script provided in the root of the repository is just for testing purposes. You need a tool which could do the following:

* deploy a stable branch of your repo.
* can rollback a version in case you spot critical errors in your deployment.
* avoid concurrent deployment issues.

Simple and tiny solutions are [Deployer](https://deployer.org/) and [PM2](https://pm2.keymetrics.io/), that can be both configured to deploy anything you want.
