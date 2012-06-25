PupcakePHPActiveRecord
======================

Pupcake PHPActiveRecord is a plugin component for Pupcake Framework to work with the PHP ActiveRecord library (http://www.phpactiverecord.org/)

##Installation:

####install package "Pupcake/PHPActiveRecord" using composer (http://getcomposer.org/)

###Usage

Before we get started, we would like to set up the following file structures for our web application:

* public/index.php --- the main index file
* vendor --- the vendor folder, storing all composer packages
* vendor/autoload.php --- the comoser's auto-generated file for class autoloading
* classes --- the classes folder 
* classes/Model --- the folder for our models using phpactiverecord
* packages --- the folder to store 3rd party libraries

We first need to check out php activerecord library from github: https://github.com/kla/php-activerecord.git 

git clone https://github.com/kla/php-activerecord.git packages/php-activerecord

Now we are going to write classes/Model/User.php
```php
<?php

namespace Model;

use ActiveRecord;

class User extends ActiveRecord\Model
{
}
```
The User model wraps around the users table.


Now we are going to write our public/index.php

```php
<?php
//Assuming this is public/index.php and the composer vendor directory is ../vendor

require_once __DIR__.'/../vendor/autoload.php';

$loader = new Composer\Autoload\ClassLoader();
$loader->add("Model", "../classes");
$loader->register();

$app = new Pupcake\Pupcake();

$app->usePlugin("Pupcake.PHPActiveRecord", array(
    'phpactiverecord_loader' => __DIR__."/../packages/php-activerecord/ActiveRecord.php", // this is the phpactiverecord's main loader file
    'configuration_callback' => function($cfg)
    {
        $cfg->set_connections(array('development' => 'mysql://root:@localhost/phpactiverecords'));
    }, //this is the configuration callback for phpactiverecord
));

$app->get("user/create", function($req, $res){
    try{
        $user = new Model\User(array('name' => md5(time()), 'email' => time().'@dummy.com'));
        $user->save();
        $res->send("User is created successfully!");
    }
    catch(Exception $e){
        $res->send("Fail adding user!");
    }
});

$app->run();
```
In public/index.php, we use composer's class loader to register namespace Model so that we can use Model/User class
We then specify the phpactiverecord's main loader file location and the configuration callback function.
Then in the user/create route, we create a random user record.

We must make sure that out local mysql database has the phpactiverecords database setup and with a users table which has the following fields:
* id 
* name
* email
