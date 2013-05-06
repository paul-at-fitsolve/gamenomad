<?php

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', 
        realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', 
        (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));
// Ensure library/ is on include_path
set_include_path(
        implode(PATH_SEPARATOR, 
                array(
                        realpath(APPLICATION_PATH . '/../library'),
                        realpath(
                                APPLICATION_PATH .
                                         '/../application/views/scripts'),
                        realpath(
                                APPLICATION_PATH .
                                 '/../library/php-google-map-api/releases/3.0/src'),
                        realpath(
                                APPLICATION_PATH .
                                 '/../application/models/Entities'),
                        get_include_path()
                )));
require_once 'Zend/Application.php';
$application = new Zend_Application(APPLICATION_ENV, 
        APPLICATION_PATH . '/configs/application.ini');

$application->bootstrap();
