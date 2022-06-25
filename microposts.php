<?php
session_start();

require_once('includes/init.php');
include('filters/auth_filter.php');

if(isset($_POST['publish']))
{
    if(!empty($_POST['content']))
    {
        extract($_POST);

        if(strlen($content) >= 3 && strlen($content) <= 140)
        {
            create_micropost_for_the_current_uer($content);

            set_flash('Votre statut a été mis à jour !', 'success');
        }
        else if(strlen($content) < 3)
        {
            set_flash("Contenu trop cours !", 'warning');
        }else{
            set_flash('Contenu trop long !', 'warning');
        }
    }else{
        set_flash('Aucun contenu n\'a été fourni !', 'danger');
    }
}

redirect('profile.php?id='.get_session('user_id'));