<?php

session_start();

// Supprimer l'entrée en bdd des informations au niveau de auth_tokens
require('config/database.php');
$q = $db->prepare("DELETE FROM auth_tokens WHERE id = ?");
$q->execute([$_SESSION['user_id']]);

// Reinitialisation de la sesion
$session_keys_white_list = ['locale'];
$niew_session = array_intersect_key($_SESSION, array_flip($session_keys_white_list));
$_SESSION = $niew_session;

// Supprimer les cookies et redirection
setcookie('auth', "", time()-3600);
header('Location: login.php');

?>