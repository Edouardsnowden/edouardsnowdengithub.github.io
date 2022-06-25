<?php
session_start();

require_once('includes/init.php');
include('filters/guest_filter.php');

if(isset($_POST['register']))
{
    if(not_empty(['name', 'pseudo', 'email', 'password', 'password_confirm']))
    {
        $errors = [];
        extract($_POST);

        if(strlen($pseudo) < 3)
        {
            $errors[] = 'Pseudo trop cours (minimum trois caractères) !';
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $errors[] = 'Adresse email invalide !';
        }
        if(strlen($password) < 6)
        {
            $errors[] = 'Mot de passe trop cours (minimum six caractères) !';
        }else{
            if(!$password == $password_confirm)
            {
                $errors[] = 'Les deux mots de passes ne concordent pas !';
            }
        }
        if(is_already_in_use('pseudo', $pseudo, 'users'))
        {
            $errors[] = 'Pseudo déjà utilisé !';
        }
        if(is_already_in_use('email', $email, 'users'))
        {
            $errors[] = 'E-mail déjà utilisé !';
        }

        if(count($errors) == 0)
        {
            // Envois de mail d'activation

            $to = $email;
            $subject = WEBSITE_NAME."-ACTIVATION DE COMPTE";
            $password = password_hash($password, PASSWORD_BCRYPT);
            $token = sha1($pseudo.$email.$password);

            // require_once('test.php');

            ob_start();
            require_once('templates/emails/activation.tmpl.php');
            $content = ob_get_clean();

            $headers = 'MIME-Version: 1.0'."\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";

            mail($to, $subject, $content, $headers);

            //Informer l'utlisateur pour qu'il vérifie le message

            set_flash("Mail d'activation envoyer !", "success");

            $q = $db->prepare('INSERT INTO users(name, pseudo, email, password)
                                VALUES(:name, :pseudo, :email, :password)');
            $q->execute([
                "name" => $name,
                "pseudo" => $pseudo,
                "email" => $email,
                "password" => $password
            ]);

            redirect('index.php');
        }else{
            save_input_data();
        }

    }else{
        $errors[] = 'Veillez SVP vérifier que tous les champs ont été bien remplit !';
        save_input_data();
    }
}else{
    cleer_input_data();
}



require_once('views/register.view.php');