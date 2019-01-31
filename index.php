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

$f3->route('POST /personal', function($f3){
    /*
    $_SESSION=array();
    if(isset($_POST['color'])){
        $color=$_POST['color'];
        if(validColor($color)){
            $_SESSION['color']=$color;
            $f3->reroute('/result');
        }else{
            $f3->set("error['color']","Please select a color.");
        }
    }
    */
    //print_r($_POST);
//    $_SESSION["animal"] = $_POST[animal];
//    $f3->set("animal", $_SESSION["animal"]);
    //print_r($_SESSION);

    $template = new Template();
    echo $template->render('views/personal.html');
});

$f3->route('POST /personal', function($f3){
    /*
    $_SESSION=array();
    if(isset($_POST['color'])){
        $color=$_POST['color'];
        if(validColor($color)){
            $_SESSION['color']=$color;
            $f3->reroute('/result');
        }else{
            $f3->set("error['color']","Please select a color.");
        }
    }
    */
    //print_r($_POST);
//    $_SESSION["animal"] = $_POST[animal];
//    $f3->set("animal", $_SESSION["animal"]);
    //print_r($_SESSION);

    $template = new Template();
    echo $template->render('views/personal.html');
});

$f3->route('POST /profile', function($f3){

    $template = new Template();
    echo $template->render('views/profile.html');
});

$f3->route('POST /interests', function($f3){

    $template = new Template();
    echo $template->render('views/interests.html');
});

$f3->route('POST /summary', function($f3){

    $template = new Template();
    echo $template->render('views/summary.html');
});

//run fat free
$f3->run();