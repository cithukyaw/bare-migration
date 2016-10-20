# CodeIgniter Bare Migration Generator

A simple PHP command line tool to generate a CodeIgniter blank migration class file. It is very similiar to `doctrine:migrations:generate` of Symfony [DoctrineMigrationsBundle](http://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html).

## Installation using Composer

    composer require phplucidframe/bare-migration

or

    php composer.phar require phplucidframe/bare-migration

## Installation without Composer

If you don't want to use Composer, you can download the zip file and unzip it to your project vendor folder. However, make sure the directory path is `/vendor/phplucidframe/bare-migration/` where the script file `ci` should exist.

## Example Usage

    $ php vendor/phplucidframe/bare-migration/ci migration:bare add_new_post_table

OR, you can also CD to the package directory.

    $ cd vendor/phplucidframe/bare-migration
    $ php ci migration:bare add_new_post_table

The above example will create a new migration file `application/migrations/{YmdHis}_add_new_post_table.php` where `{YmdHis}` would be the current timestamp.
