<?php
session_start();

require_once('includes/init.php');
include('filters/auth_filter.php');

if(isset($_POST['change_password']))
{
    $errors = [];

    if(not_empty(['current_password', 'niew_password', 'niew_password_confirmation']))
    {
        extract($_POST);
        
        if(strlen($niew_password) < 6)
        {
            $errors[] = 'Mot de passe trop cours (minimum six caractères) !';
        }else{
            if($niew_password != $niew_password_confirmation)
            {
                $errors[] = 'Les deux nouveaux mots de passes ne sont pas identiques !';
            }
        }

        if(count($errors) == 0)
        {
            $q = $db->prepare("SELECT password AS hashed_password FROM users 
            WHERE (id = :id) 
            AND active = '1'");
            $q->execute([
            "id" => get_session('user_id')
            ]);

            $user = $q->fetch(PDO::FETCH_OBJ);

            if($user && password_verify($current_password, $user->hashed_password))
            {

                $q = $db->prepare('UPDATE users SET password = :password WHERE id = :id');
                $q->execute([
                    "password"  => password_hash($niew_password, PASSWORD_BCRYPT),
                    "id"        => get_session('user_id')
                ]);
                
                set_flash("Félicitations ! Votre mot de passe a été bel et bien modifier.");
                redirect('profile.php?id='.get_session('user_id'));

            }else{
                $errors[] = "Le mot de passe indiqué est incorrecte !";
            }
        }

    }else{
        $errors[] = 'Veillez SVP vérifier que tous les champs ont été bien remplit !';
        save_input_data();
    }
}else{
    cleer_input_data();
}



require_once('views/change_password.view.php');