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
ini_set('display_errors',TRUE);
error_reporting(E_ALL);

//require autoload
require_once('vendor/autoload.php');
require_once('model/validation-functions.php');

session_start();

//create and instance of the Base class
$f3 = Base::instance();

$f3->set('indoor', array('tv','movies','cooking','board games','puzzles','reading','playing cards','video games'));
$f3->set('outdoor', array('hiking','biking','swimming','collecting',
    'walking','climbing'));

//turn on fat free error reporting
$f3->set('DEBUG',3);

//define a default route
$f3->route('GET /', function(){
    $view = new View();
    echo $view->render('views/home.html');
});

$f3->route('GET|POST /personal',
    function($f3){

    $_SESSION = array();
    print_r($_POST);

    if(isset($_POST['fname'])){
        $fname = $_POST['fname'];
            if (validName($fname)) {
                $_SESSION['fname'] = $fname;
            } else {
                $f3->set("errors['fname']", "First name contains non alphabetic characters.");
            }
    }
    if(isset($_POST['lname'])){
        $lname = $_POST['lname'];
        if (validName($lname)) {
            $_SESSION['lname'] = $lname;
        } else {
            $f3->set("errors['lname']", "Last name contains non alphabetic characters.");
        }
    }
    if(isset($_POST['age'])){
        $age = $_POST['age'];
        if(validAge($age)){
            $_SESSION['age'] = $age;
        } else{
            $f3->set("errors['age']","Provided age under 18");
        }
    }
    if(isset($_POST['gender'])){
        $_SESSION['gender'] = $_POST['gender'];
    }

    if(isset($_POST['phone'])){
        $phone = $_POST['phone'];
        if(validPhone($phone)){

            //todo inset into phone string '-' marks
            $_SESSION['phone'] = $phone;
        }else{
            $f3->set("errors['phone']","Unrecognized phone number provided");
        }
    }

    //todo make me actually stop things
    if(!isset($errors['fname']) and isset($fname))
    {
       $f3->reroute('/profile');
    }

    $template = new Template();
    echo $template->render('views/personal.html');
});

$f3->route('GET|POST /profile', function($f3){

    print_r($_SESSION);

    $_SESSION['email'] = null;
    $_SESSION['state'] = null;
    $_SESSION['seeking'] = null;
    $_SESSION['bio'] = null;

    if(isset($_POST['email'])){
        $email = $_POST['email'];
        $_SESSION['email'] = $email;
    }
    if(isset($_POST['state'])){
        $state = $_POST['state'];
        $_SESSION['state'] = $state;
    }
    if(isset($_POST['seeking'])){
        $seeking = $_POST['seeking'];
        $_SESSION['seeking'] = $seeking;
    }
    if(isset($_POST['bio'])){
        $bio = $_POST['bio'];
        $_SESSION['bio'] = $bio;
    }

    if(!empty($_POST)){
        $f3->reroute('/interests');
    }


    $template = new Template();
    echo $template->render('views/profile.html');
});

$f3->route('GET|POST /interests',
    function($f3){

    print_r($_SESSION);

    $_SESSION['indoor'] = array();
    $_SESSION['outdoor'] = array();
    print_r($_POST);

    if(isset($_POST['outdoor'])) {
        $outdoor = $_POST['outdoor'];
        foreach ($outdoor as $item) {
            if (validOutdoor($item)) {
                $_SESSION['outdoor'][] = $item;
            } else {
                $f3->set("errors['outdoor']", "Invalid value submitted.");
            }
        }
    }
    if(isset($_POST['indoor'])) {
        $indoor = $_POST['indoor'];
        foreach ($indoor as $item)
        {
            if(validIndoor($item))
            {
                $_SESSION['indoor'][] = $item;
            }else
            {
                $f3->set("errors['indoor']","Invalid value submitted.");
            }
        }
    }

    //TODO replace !empty with a away to allow no interests to be selected
    if(!isset($errors['outdoor']) and !isset($errors['outdoor']) and !empty($_POST)){
        $f3->reroute('/summary');
    }

    $template = new Template();
    echo $template->render('views/interests.html');
});

$f3->route('GET|POST /summary', function($f3){

    //print_r($_SESSION);

    $template = new Template();
    echo $template->render('views/summary.html');
});

//run fat free
$f3->run();