<?php
session_start();

require_once('includes/init.php');
include('filters/guest_filter.php');


if(!empty($_GET['p']) && is_already_in_use('pseudo', $_GET['p'], 'users') && !empty($_GET['token']))
{
    $pseudo = $_GET['p'];
    $token = $_GET['token'];

    $q = $db->prepare('SELECT id, email, password FROM users WHERE pseudo = ?');
    $q->execute([$pseudo]);

    $data = $q->fetch(PDO::FETCH_OBJ);
    
    $token_verif = sha1($pseudo.$data->email.$data->password);

    if($token == $token_verif)
    {
        $q = $db->prepare("UPDATE users SET active = '1' WHERE pseudo = ?");
        $q->execute([$pseudo]);

        $q = $db->prepare("INSERT INTO friends(user_id1, user_id2, status) VALUES (?, ?, ?)");
        $q->execute([$data->id, $data->id, '2']);
        
        set_flash('Votre compte a été belle et bien activé.');
        redirect('login.php');

    }else{
        set_flash('Jeton de sécurité invalides', 'danger');
        redirect('index.php');
    }

}else{
    redirect('index.php');
}