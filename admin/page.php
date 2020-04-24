<?php


// Just For Studying


$do = '';

if(isset($_GET['do']))
{
    $do = $_GET['do'];
    
}
else
{
    $do = 'manage';
    
}

if($do == 'manage')
{
    echo 'this manage';
}
elseif($do == 'Add')
{
    echo "this add";
}
elseif($do == 'delete')
{
    echo "this delete";
}
else
{
    echo "this page notFound";
}