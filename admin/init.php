<?php

include 'connect.php';


//Routes

$tpl = 'includes/templates/'; //Tamplate Directory
$lang = 'includes/languages/'; //Language Directory
$css = 'layout/css/'; //CSS Directory
$js = 'layout/js/'; //JS Directory
$func = 'includes/functions/';


//Include The Important File

include $lang.'english.php';
include $func.'functions.php';
include $tpl . 'header.php';

//Include Navbar On All Pages Except The One With $noNavbar Variable
if(!isset($noNavbar))
{
    include $tpl . 'navbar.php';
}

