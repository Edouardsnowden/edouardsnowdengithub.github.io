<?php
session_start();

require_once('includes/init.php');
include('filters/auth_filter.php');

if(!empty($_GET['id']))
{
    $data = find_code_by_id($_GET['id']);

    if(!$data)
    {
        redirect('share_code.php');
    }
}else{
    redirect('share_code.php');
}



require_once('views/show_code.view.php');