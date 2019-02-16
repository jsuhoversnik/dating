<?php
/**
 * @author Jake Suhoversnik
 * @version 1.0
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
$f3->set('states',array('Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'District of Columbia', 'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'));

//turn on fat free error reporting
$f3->set('DEBUG',3);

//define a default route
$f3->route('GET /', function(){
    $view = new View();
    echo $view->render('views/home.html');
});

$f3->route('GET|POST /personal', function($f3)
{
    //wipe our session values we're using if we didnt reroute
    $_SESSION = array();

    if(isset($_POST['fname']))
    {
        $fname = $_POST['fname'];
            if (validName($fname))
            {
                $_SESSION['fname'] = $fname;
            }
            else
            {
                $f3->set("errors['fname']", "First name contains non alphabetic characters.");
            }
    }
    if(isset($_POST['lname']))
    {
        $lname = $_POST['lname'];
        if (validName($lname))
        {
            $_SESSION['lname'] = $lname;
        }
        else
        {
            $f3->set("errors['lname']", "Last name contains non alphabetic characters.");
        }
    }
    if(isset($_POST['age']))
    {
        $age = $_POST['age'];
        if(validAge($age))
        {
            $_SESSION['age'] = $age;
        }
        else
        {
            $f3->set("errors['age']","Provided age under 18");
        }
    }
    if(isset($_POST['gender']))
    {
        $_SESSION['gender'] = $_POST['gender'];
    }

    if(isset($_POST['phone']))
    {
        $phone = $_POST['phone'];
        if(validPhone($phone))
        {
            $_SESSION['phone'] = $phone;
        }
        else
        {
            $f3->set("errors['phone']","Unrecognized phone number provided");
        }
    }
    if(isset($_POST['premium']))
    {
        $_SESSION['premium'] = $_POST['premium'];
    }

    if(!empty($_POST['fname']) and !empty($_POST['lname']) and !empty($_POST['age']) and !empty($_POST['phone']))
    {
        if(!isset($errors['fname']) and !isset($errors['lname']) and !isset($errors['age']) and !isset($errors['phone']))
        {
            //add formatting to our phone number
            $_SESSION['phone'] = substr_replace($_SESSION['phone'], "-",3,0);
            $_SESSION['phone'] = substr_replace($_SESSION['phone'], "-",7,0);

            if(isset($_SESSION['premium']))
            {
                $member = new PremiumMember($_SESSION['fname'],$_SESSION['lname'],$_SESSION['age'],$_SESSION['gender'],$_SESSION['phone']);
            }
            else
            {
                $member = new Member($_SESSION['fname'],$_SESSION['lname'],$_SESSION['age'],$_SESSION['gender'],$_SESSION['phone']);
            }

            $_SESSION['member'] = $member;

            $f3->reroute('/profile');
        }
    }
    $template = new Template();
    echo $template->render('views/personal.html');
});

$f3->route('GET|POST /profile', function($f3)
{
    //wipe our session values we're using if we didnt reroute
    $_SESSION['email'] = null;
    $_SESSION['state'] = null;
    $_SESSION['seeking'] = null;
    $_SESSION['bio'] = null;

    if(isset($_POST['email']))
    {
        $email = $_POST['email'];
        $_SESSION['email'] = $email;

        if(empty($_POST['email']))
        {
                $f3->set("errors['email']","invalid email provided");
        }
    }
    if(isset($_POST['state']))
    {
        $state = $_POST['state'];
        $_SESSION['state'] = $state;
    }
    if(isset($_POST['seeking']))
    {
        $seeking = $_POST['seeking'];
        $_SESSION['seeking'] = $seeking;
    }
    if(isset($_POST['bio']))
    {
        $bio = $_POST['bio'];
        $_SESSION['bio'] = $bio;
    }

    //add values to member object
    $_SESSION['member']->setEmail($_SESSION['email']);
    $_SESSION['member']->setState($_SESSION['state']);
    $_SESSION['member']->setSeeking($_SESSION['seeking']);
    $_SESSION['member']->setBio($_SESSION['bio']);

    if(!empty($_POST['email']) and isset($_SESSION['premium']))
    {
        $f3->reroute('/interests');
    }
    if(!empty($_POST['email']))
    {
        $f3->reroute('/summary');
    }

    $template = new Template();
    echo $template->render('views/profile.html');
});

$f3->route('GET|POST /interests', function($f3)
{
    //wipe our session values we're using if we didnt reroute
    $_SESSION['indoor'] = array();
    $_SESSION['outdoor'] = array();

    if(isset($_POST['outdoor']))
    {
        $outdoor = $_POST['outdoor'];
        foreach ($outdoor as $item)
        {
            if (validOutdoor($item))
            {
                $_SESSION['outdoor'][] = $item;
            }
            else
            {
                $f3->set("errors['outdoor']", "Invalid value submitted.");
            }
        }
    }
    if(isset($_POST['indoor']))
    {
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

    if($f3->get("errors['indoor']") == null and $f3->get("errors['outdoor']") == null)
    {
        if(isset($_POST['submit']))
        {
            if(isset($_SESSION['premium']))
            {
                $_SESSION['member']->setInDoorInterests($_SESSION['indoor']);
                $_SESSION['member']->setOutDoorInterests($_SESSION['outdoor']);
            }

            $f3->reroute('/summary');
        }
    }

    $template = new Template();
    echo $template->render('views/interests.html');
});

$f3->route('GET|POST /summary', function($f3)
{
    $template = new Template();
    echo $template->render('views/summary.html');
});

//run fat free
$f3->run();