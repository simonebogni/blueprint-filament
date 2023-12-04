[Back to index](../README.md)

# Base app - Laravel + Lando

<!-- TOC -->
* [Base app - Laravel + Lando](#base-app---laravel--lando)
  * [Laravel installation](#laravel-installation)
  * [Lando configuration](#lando-configuration)
    * [Create config file](#create-config-file)
    * [Manage containers](#manage-containers)
    * [Modify .env file](#modify-env-file)
<!-- TOC -->

## Laravel installation

This is the baseline for the project.\
It's a fresh installation of Laravel 10, served using Lando.

Follow the steps at https://laravel.com/docs/10.x/installation

Run `composer create-project laravel/laravel name-of-the-app`

## Lando configuration

Lando needs to be installed globally.
More info here https://docs.lando.dev/getting-started/installation.html

### Create config file
Create a config file for Lando, by running `lando init`.\
Select:

- codebase: current working directory
- recipe: lamp
- webroot: /public
- name: your app name

This will create a .lando.yml file with the following content:

```yaml
name: your-app-name
recipe: lamp
config:
  webroot: /public
```

Modify it to be like this:

```yaml
name: your-app-name
recipe: lamp
config:
  php: '8.2'
  via: 'apache:2.4'
  database: 'mysql:5.7'
  cache: redis
  xdebug: false
  webroot: public
proxy:
  appserver:
    - your-app-name.lndo.site
services:
  cache:
    type: redis
  database:
    portforward: 23507
```

This takes care of configuring the containers in docker for the webserver (apache), the DB server (MySQL) and the cache (Redis).\
It also take care of the port forwarding, using the local port 23507 to communicate with the DB container.

### Manage containers

To start the containers, use `lando start`.\
To stop them, use `lando stop`.\
To completely destroy the containers, use `lando destroy`.\
To view the information of the containers, use `lando info`.\
This is especially useful of you to see the ports for the DB connection with the container (external connection and creds):

```
{ 
    service: 'database',
    urls: [],
    type: 'mysql',
    healthy: true,
    internal_connection: { 
        host: 'database', 
        port: '3306'
    },
    external_connection: { 
        host: '127.0.0.1', 
        port: '23507'
    },
    healthcheck: 'bash -c "[ -f /bitnami/mysql/.mysql_initialized ]"',
    creds: { 
        database: 'lamp', 
        password: 'lamp', 
        user: 'lamp'
    },
    config: {},
    version: '5.7',
    meUser: 'www-data',
    hasCerts: false,
    api: 3,
    hostnames: [ 'database.blueprintandfilament.internal' ]
}
```

After starting the app, you should see something like:

```text
Here are some vitals:

 NAME      blueprint-and-filament                                                       
 LOCATION  /Users/simonebogni/Desktop/projects/tutorials/blueprint-filament/00_base_app 
 SERVICES  appserver, database, cache                                                   
 URLS                                                                                   
  ✔ APPSERVER URLS
    ✔ https://localhost:50243 [200]
    ✔ http://localhost:50244 [200]
    ✔ http://blueprintandfilament.lndo.site/ [200]
    ✔ https://blueprintandfilament.lndo.site/ [200]
```
[200] means that the container is working correctly.

### Modify .env file

Modify the DB-related fields in the .env file.\
One set of settings is for the use within the Lando container (from web server to the DB server).\
Another is for the use from outside the app server (like when running commands from the localhost).\
Enable one set or the other depending on the need.

```
#  Lando
DB_CONNECTION=mysql
DB_HOST=database
DB_PORT=3306
DB_DATABASE=lamp
DB_USERNAME=lamp
DB_PASSWORD=lamp

#  Localhost
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=23507
# DB_DATABASE=lamp
# DB_USERNAME=lamp
# DB_PASSWORD=lamp
```

Enable the set of settings to connect with localhost.\
Run the migration command to prepare the base database structure: `php artisan migrate`
