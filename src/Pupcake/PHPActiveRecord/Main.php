<?php
/**
 * The PHPActiveRecord plugin
 */

namespace Pupcake\PHPActiveRecord;

use Pupcake;

class Main extends Pupcake\Plugin
{
    public function load($config = array())
    {
        if(!isset($config['phpactiverecord_loader'])){
            throw new ConfigurationException("Missing PHP Active Record Loader File!");
        }
        else if(!is_readable($config['phpactiverecord_loader'])){
            throw new ConfigurationException("Fail reading PHP Active Record Loader File!");
        }
        else if(!isset($config['configuration_callback'])){
            throw new ConfigurationException("Missing configuration callback for PHP Active Record");
        } 
        else if(!is_callable($config['configuration_callback'])){
            throw new ConfigurationException("Invalid configuration callback for PHP Active Record");
        }
        else{
            //now everything is good, load phpactiverecord
            require $config['phpactiverecord_loader'];
            \ActiveRecord\Config::initialize($config['configuration_callback']);
        }
    }
}
