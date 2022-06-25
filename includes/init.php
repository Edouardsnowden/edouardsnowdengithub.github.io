<?php

require_once('config/database.php');
require_once('includes/constants.php');
require_once('bootstrap/locale.php');
require_once('includes/functions.php');

if(!empty($_COOKIE['pseudo']) && !empty($_COOKIE['user_id']))
{
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['pseudo'] = $_COOKIE['pseudo'];
    $_SESSION['avatar'] = $_COOKIE['avatar'];
}

//Récupération du nombre total de notifications non lues
$q = $db->prepare("SELECT id FROM notifications WHERE subject_id = ? AND seen = '0'");

$q->execute([get_session('user_id')]);

$notifications_count = $q->rowCount();

auto_login();