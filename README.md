# Brainr Web App

## Installation

### General requirements
- PHP 7.4;
- Composer.

### General installation
- Run `composer install`;
- Copy `.env.example` to `.env`;
- Run `php artisan key:generate`;
- Run `php artisan clear`;
- Run `php artisan storage:link`;
- Run `npm install`;

### MacOS

#### Requirements
- Valet (installed via Homebrew);
- Docker.

#### Installation
- Run `docker run -e MYSQL_ROOT_PASSWORD=pass -e MYSQL_DATABASE=brainr -p 3306:3306 -d mysql`;
- Run `valet link brainr` from the `/public` directory;
- Edit your `.env` file:
  - Set `DB_USERNAME` to `root`;
  - Set `DB_PASSWORD` to `pass`;
  - Set `APP_URL` to `http://brainr.test`.
- Run `php artisan migrate`;
- Run `php artisan db:seed`;
###### Account =  admin@brainr.com:pass
- Run `php artisan storage:link`;
- Go to /config/sanctum.php and set `'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'localhost,127.0.0.1'))`

- The app can now be visited at `http://brainr.test`.

### Linux

#### Requirements
- Docker.

#### Installation
- Run `docker run -e MYSQL_ROOT_PASSWORD=pass -e MYSQL_DATABASE=brainr -p 3306:3306 -d mysql`;
- Edit your `.env` file:
  - Set `DB_USERNAME` to `root`;
  - Set `DB_PASSWORD` to `pass`;
  - Set `APP_URL` to `http://localhost:8000`.
- Run `php artisan migrate`;
- Run `php artisan serve`;
- The app can now be visited at `http://localhost:8000`.

### Windows

#### Requirements
- VirtualBox (requirement for Vagrant);
- Vagrant;
- Homestead.

#### Installation
- Configure Homestead to point to the project folder(not the public folder);
- Edit your `.env` file:
  - Set `DB_USERNAME` to `homestead`;
  - Set `DB_PASSWORD` to `secret`;
  - Set `APP_URL` to the url that you specified in the Homestead configuration.
- SSH into the Vagrant box and run `mysql`:
  - Then run `CREATE DATABASE brainr;`;
  - Then run `exit`;
  - Then run `php artisan migrate`.
- The app can now be visited at the url specified by Homestead.

#### Note
In the case you actually decided to go with Windows and end up with problems related to PowerShell try running `Set-ExecutionPolicy RemoteSigned`.

#### Note on GoogleMapAPI Key
To update the Google Maps API key, you need to edit `.env` file and `[project_directory/resources/js/views/profile/helpers/exports.js]` file.

