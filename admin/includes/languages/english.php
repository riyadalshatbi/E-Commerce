<?php

function lang($phrase){
    
    static $lang = array(
      
        //Navbar Links
        
        "HOME_ADMIN" => "HOME",
        "CATEGORIES" => "Categories",
        "ITEMS"      => "Items",
        "MEMBERS"    => "Members",
        "COMMENTS"   => "Comments",
        "STATISTICS" => "statistics",
        "LOGS"       => "logs",
        "" => "",
        "" => "",
        "" => "",
        "" => "",
        "" => ""
    );
    
    return $lang[$phrase];
}