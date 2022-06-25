<?php
session_start();

require_once('includes/init.php');
include('filters/auth_filter.php');

if(!empty($_GET['id']))
{
    $data = find_code_by_id($_GET['id']);
    
    if(!$data)
    {
        $code = "";
    }else{
        $code = $data->code;
    }
}else{
    $code = "";
}

//Si le formulaire à été soumis
if(isset($_POST['save']))
{
    if(not_empty(['code']))
    {
        extract($_POST);

        $q = $db->prepare("INSERT INTO codes(code) VALUES(?)");
        $success = $q->execute([$code]);

        if($success)
        {
            // Afficharge du code source
            $id = $db->LastInsertId();

            $fullURL = WEBSITE_URL.'/show_code.php?id='.$id;
            create_micropost_for_the_current_uer('Je viens de publier un nouveau code source: '.$fullURL);

            redirect('show_code.php?id='.$id);

        }else{
            set_flash('Erreur lors de l\'ajout du code source . Veillez reessayez svp !');
            redirect('share_code.php');
        }

    }else{
        redirect('share_code.php');
    }
}



require_once('views/share_code.view.php');