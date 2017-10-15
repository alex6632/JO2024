JO2024
======

A Symfony project created on October 10, 2017, 11:04 pm.


Install
-------
On first install run this command:
```shell
composer install
```
_More details see: [composer.json](/composer.json)_

Puis dans le dossier /web:
```shell
npm install
```
_More details see: [package.json](web/package.json)_

BDD
-------
// After creation of database, run this command to update data:
```shell
php bin/console doctrine:schema:update --force
```

Compile
-------
// Run compilation task once. (in /web folder)
```shell
gulp dist
```

### OTF version
// Run tasks whenever watched files change.
```shell
gulp watch
```
_More tasks see: [Gulpfile.js](web/Gulpfile.js)_