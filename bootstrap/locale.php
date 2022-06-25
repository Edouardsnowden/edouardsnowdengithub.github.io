<?php

$authorized_langagues = ['fr', 'en'];

if(!empty($_GET['lang']) && in_array($_GET['lang'], $authorized_langagues))
{
    $_SESSION['locale'] = $_GET['lang'];
}else{
    if(empty($_SESSION['locale']))
    {
        $_SESSION['locale'] = $authorized_langagues[0];
    }
}

// Include all locals filles
$locales_files = glob("locales/*");
foreach($locales_files as $files)
{
    require $files;
}