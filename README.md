## Installation
***

This application is a photo album, Admin can add, delete photos, and Users can just see photos

A little intro about the installation.
```
$ git clone git@github.com:EliseAndaloro/advercity.git
$ cd ../path/to/the/file

To recover the vendor directory, run:
$ composer install

To make the migrations of database, run :
$ php bin/console doctrine:migrations:migrate

To fill the database with some datas, run :
$ php bin/console doctrine:fixtures:load

To start the symfony server, run :
$ symfony server:start

Then go to 127.0.0.1:8000/photos

To add a photo, click the "Add a photo" button, then login with "admin@exemple.fr" as email and "admin" as password. 