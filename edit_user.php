<?php

session_start();

require_once('includes/init.php');
include('filters/auth_filter.php');

if(!empty($_GET['id']) && $_GET['id'] == get_session('user_id'))
{
    //Récuperer les infos sur l'user dans la bdd en utilisant son id
    $user = find_user_by_id($_GET['id']);


    if(!$user)
    {
        redirect('index.php');
    }
}else{
    redirect('profile.php?id='.get_session('user_id'));
}

if(isset($_POST['update']))
{
    $errors = [];

    if(not_empty(['name', 'city', 'country', 'sex', 'bio']))
    {
        extract($_POST);

        $q = $db->prepare("UPDATE users SET name = :name, city = :city, country = :country, sex = :sex,
                                            twitter = :twitter, github = :github,
                                            available_for_hiring = :available_for_hiring, bio = :bio
                                            WHERE id = :id");
        $q->execute([
            "name" => $name,
            "city" => $city,
            "country"=> $country,
            "sex" => $sex,
            "twitter" => $twitter,
            "github" => $git,
            "available_for_hiring" => !empty($available_for_hiring)? '1' : '0',
            "bio" => $bio,
            "id" => get_session('user_id'),
        ]);

                    
        if(isset($_FILES['avatar']) && !empty($_FILES['avatar']['name']))
        {
            $sizeMax = 209700000.15;
            $extension = array('jpg', 'png', 'jpeg', 'gif');
        
            if($_FILES['avatar']['size'] <= $sizeMax)
            {
                $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1 ));
                if(in_array($extensionUpload, $extension))
                {
                    $randomFileName = sha1(uniqid(rand()));
                    $targetFolder = "Users/avatar/".get_session('user_id')."/";
                    $path = $targetFolder.get_session('pseudo').$randomFileName.".".$extensionUpload;

                    if(!file_exists($targetFolder))
                    {
                        mkdir($targetFolder, 0755, true);
                    }
                    $result = move_uploaded_file($_FILES['avatar']['tmp_name'], $path);

                    if($result)
                    {
                        $query = $db->prepare("UPDATE users SET avatar = :avatar WHERE id = :id");
                        $query->execute([
                            "avatar" => get_session('pseudo').$randomFileName.'.'.$extensionUpload,
                            "id" => $_GET['id']
                        ]);
                    }else{
                        $errors[] = "Echec lors de l'ajout du fichier";
                    }
                }else{
                    $errors[] = "Format de fichier incorrect !";
                }
            }else{
                $errors[] = "Image trop volumineux ! maximum 2Mo.";
            }
        }else{
            $errors[] = "Ajouter une image";
        }

    }else{
        save_input_data();
        $errors[] = 'Veillez remplir tous les champs marqués d\'un (*).';
    }

    if(!$errors)
    {
        set_flash("Félicitations ! Votre profil a été mis à jour.");
        redirect('profile.php?id='.get_session('user_id'));
    }

}else{
    cleer_input_data();
}

require('views/edit_user.view.php');