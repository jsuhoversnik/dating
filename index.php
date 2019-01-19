<?php
/**
 * Created by PhpStorm.
 * User: Jake
 * Date: 1/17/2019
 * Time: 4:55 PM
 *
 * index.php
 * Fat Free Framework with default route set for dating website
 */

//turn on error reporting
ini_set('display_errors',1);
error_reporting(E_ALL);

//require autoload
require_once('vendor/autoload.php');

//create and instance of the Base class
$f3 = Base::instance();
//turn on fat free error reporting
$f3->set('DEBUG',3);

//define a default route
$f3->route('GET /', function(){
    $view = new View();
    echo $view->render('views/home.html');
});

//run fat free
$f3->run();