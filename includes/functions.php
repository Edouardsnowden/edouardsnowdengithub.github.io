<?php

if(!function_exists("get_session"))
{
    function get_session($key)
    {
        if($key)
        {
            return !empty($_SESSION[$key])? $_SESSION[$key]: null;
        }
    }
}

/* COOKIES BLOCK */

//Retourne le nombre d'enregistrements trouvés respectant une certaine condition
if(!function_exists('cell_count'))
{
    function cell_count($table, $field_name, $field_value)
    {
        global $db;

        $q = $db->prepare("SELECT * FROM $table WHERE $field_name = ?");
        $q->execute([$field_value]);

        return $q->rowCount();
    }
}

// remember me
if(!function_exists('remember_me'))
{
    function remember_me($user_id)
    {
        global $db;

        // Generer le token de maniere aléatoire
        $token = bin2hex(random_bytes(24));

        // Generer le token de maniere aléatoire et s'assurer que ce dernier est unique
        do{
            $selector = bin2hex(random_bytes(9));
        }while(cell_count('auth_tokens', 'selector', $selector) > 0);

        // Sauuver ces infos (user_id, selector, expires(14jours), token(hashed)) en db
        $q = $db->prepare("INSERT INTO auth_tokens(expires, selector, user_id, token)
                         VALUES(DATE_ADD(NOW(), INTERVAL 14 DAY), :selector, :user_id, :token)");

        $q->execute([
            "selector" => $selector,
            "user_id" => $user_id,
            "token" => hash("sha256", $token)
        ]);

        // Creer un cookie 'aurh' (14 jours expires) httpOnly => true
        // contenue base64_encode($selector).':'.base64_encode($token)
        setcookie("auth", base64_encode($selector).':'.base64_encode($token), time()+1209600, "","", false, true);
    }
}

// Auto login
if(!function_exists('auto_login'))
{
    function auto_login()
    {
        global $db;

        // Vérifier que notre cookie 'auth' existe
        if(!empty($_COOKIE['auth']))
        {
            $split = explode(':', $_COOKIE['auth']);

            if(count($split) !== 2)
            {
                return false;
            }
            
            // Récupérer via ce cookie $selector et $token
            list($selector, $token) = $split;
            
            // Vérifier au niveau de notre auth_tokens s'il y a un enregistrement qui a pour selecteur $selector
            $q = $db->prepare("SELECT auth_tokens.token, auth_tokens.user_id, users.id, users.pseudo, users.email, users.avatar
                                FROM auth_tokens 
                                LEFT JOIN users
                                ON auth_tokens.user_id = users.id
                                WHERE selector = ? AND expires >= CURDATE()");

            // Décoder notre $selector
            $q->execute([base64_decode($selector)]);

            $data = $q->fetch(PDO::FETCH_OBJ);

            // Si on trouve un enregistrement, comparer les deux tokens
            // Si tout est bon, enregistrer toutes ces informations en session
            if($data)
            {
                if(hash_equals($data->token, hash('sha256', base64_decode($token))))
                {
                    session_regenerate_id(true);

                    $_SESSION['user_id'] = $data->user_id;
                    $_SESSION['pseudo'] = $data->pseudo;
                    $_SESSION['email'] = $data->email;
                    $_SESSION['avatar'] = $data->avatar;

                    return true;
                }
            }
        }
        return false;
    }
}

if(!function_exists('create_micropost_for_the_current_uer'))
{
    function create_micropost_for_the_current_uer($content)
    {
        global $db;

        $q = $db->prepare("INSERT INTO microposts (content, user_id, created_at) VALUES (:content, :user_id, NOW())");
        $q->execute([
            "content" => $content,
            "user_id" => get_session('user_id')
        ]);
    }
}

/* END COOKIES BLOCK */


if(!function_exists('replace_links')){
    function replace_links($texte){
        return preg_replace(array('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '/(^|[^a-z0-9_])#([a-z0-9_]+)/i'), 
                array('<a href="$1">$1</a>', '$1<a href="">@$2</a>', '$1<a href="index.php?hashtag=$2">#$2</a>'), $texte);
    }
}

if(!function_exists('links'))
{
    function links($text)
    {
        $regex_url = "#(http|https|ftp|ftps|www)+(\:|\.|\/|\/)+[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\:[0-9]+)?(\/\S*)?#";
        return preg_replace($regex_url, '<a href="$0" target="_blank">$0</a>', $text);
    }
}

/** FRIENDS BLOCK */

// Friens count
if(!function_exists('friends_count'))
{
    function friends_count($id)
    {
        global $db;

        $q = $db->prepare("SELECT status FROM friends WHERE (user_id1 = :user OR user_id2 = :user) AND status = '1'");
        $q->execute([
            "user" => $id
        ]);
        $count = $q->rowCount();

        $q->closeCursor();

        return $count;
    }
}

// Checks if a friend request has already been sent
if(!function_exists('if_a_friend_request_has_already_been_sent'))
{
    function if_a_friend_request_has_already_been_sent($id1, $id2)
    {
        global $db;

        $q = $db->prepare("SELECT user_id1, user_id2, status
                            FROM friends 
                            WHERE (user_id1 = :user_id1 AND user_id2 = :user_id2) 
                            OR (user_id1 = :user_id2 AND user_id2 = :user_id1)");
        $q->execute([
            "user_id1" => $id1,
            "user_id2" => $id2,
        ]);
        $count = $q->rowCount();

        $q->closeCursor();

        return (bool) $count;
    }
}

// Checks if a current user is friend with
if(!function_exists('current_user_is_friend_with'))
{
    function current_user_is_friend_with($second_user_id)
    {
        global $db;

        $q = $db->prepare("SELECT user_id1, user_id2, status
                            FROM friends 
                            WHERE ((user_id1 = :user_id1 AND user_id2 = :user_id2) 
                            OR (user_id1 = :user_id2 AND user_id2 = :user_id1))
                            AND status != '0'");
        $q->execute([
            "user_id1" => get_session('user_id'),
            "user_id2" => $second_user_id,
        ]);
        $count = $q->rowCount();

        $q->closeCursor();

        return (bool) $count;
    }
}

// Checks if a friend request has already been sent to display relation link
if(!function_exists('display_relation_link'))
{
    function display_relation_link($id)
    {
        global $db;

        $q = $db->prepare("SELECT user_id1, user_id2, status 
                            FROM friends 
                            WHERE (user_id1 = :user_id1 AND user_id2 = :user_id2) 
                            OR (user_id1 = :user_id2 AND user_id2 = :user_id1)");
        $q->execute([
            "user_id1" => get_session('user_id'),
            "user_id2" => $id
        ]);

        $data = $q->fetch();

        if(is_array($data) && $data['user_id1'] == $id && $data['status'] == '0')
        {
            // Afficher un lien pour accepter ou refuser la demande
            return "accept_reject_relation_link";
        }else if(is_array($data) && $data['user_id1'] == get_session('user_id') && $data['status'] == '0'){
            // Lien pour annuler la demande
            return "cancel_relation_link";
        }else if(is_array($data) && $data['status'] == '1'){
            // Lien pour suprimer la relation d'amitié
            return "delete_relation_link";
        }else{
            // Lien d'ajout
            return "add_relation_link";
        }
    }
}

/**END FRIENDS BLOCK */

/**LIKE BLOCK */

// Check if the curent user has already likes the given micropost
if(!function_exists('user_has_already_like_the_micropost'))
{
    function user_has_already_like_the_micropost($micropost_id)
    {
        global $db;

        $q = $db->prepare("SELECT id FROM micropost_like 
                       WHERE user_id = :user_id AND micropost_id = :micropost_id");
        $q->execute([
            "user_id" => get_session('user_id'),
            "micropost_id" => $micropost_id
        ]);

        return (bool) $q->rowcount();
    }
}

// Like a user micropost
if(!function_exists('like_micropost'))
{
    function like_micropost($micropost_id)
    {
        global $db;

        $q = $db->prepare("INSERT INTO micropost_like(user_id, micropost_id) 
                        VALUES (:user_id, :micropost_id)");
        $q->execute([
            "user_id" => get_session('user_id'),
            "micropost_id" => $micropost_id
        ]);

        $q = $db->prepare("UPDATE microposts SET like_count = like_count + 1 WHERE id = :micropost_id");
        $q->execute([
            "micropost_id" => $micropost_id
        ]);
    }
}

// Unlike a user micropost
if(!function_exists('unlike_micropost'))
{
    function unlike_micropost($micropost_id)
    {
        global $db;
        
        $q = $db->prepare("DELETE FROM micropost_like WHERE user_id = :user_id AND micropost_id = :micropost_id");
        $q->execute([
            "user_id" => get_session('user_id'),
            "micropost_id" => $micropost_id
        ]);

        $q = $db->prepare("UPDATE microposts SET like_count = like_count - 1 WHERE id = :micropost_id");
        $q->execute([
            "micropost_id" => $micropost_id
        ]);
    }
}

// Return the likes count of a given micropost
if(!function_exists('get_like_count'))
{
    function get_like_count($micropost_id)
    {
        global $db;

        $q = $db->prepare('SELECT like_count FROM microposts WHERE id = ?');
        $q->execute([$micropost_id]);

        $data = $q->fetch(PDO::FETCH_OBJ);

        return intval($data->like_count);
    }
}

// Return the third likers of a given micropost
if(!function_exists('get_likres'))
{
    function get_likres($micropost_id)
    {
        global $db;

        $q = $db->prepare('SELECT users.id, users.pseudo FROM users 
                            LEFT JOIN micropost_like
                            ON users.id = micropost_like.user_id
                            WHERE micropost_like.micropost_id = ?
                            ORDER BY micropost_like.id DESC
                            LIMIT 3');
        $q->execute([$micropost_id]);

        return $q->fetchAll(PDO::FETCH_OBJ);
    }
}

// Display likers of a given micropost
if(!function_exists('get_likers_text'))
{
    function get_likers_text($micropost_id)
    {
        $like_count = get_like_count($micropost_id);
        $likers = get_likres($micropost_id);

        $output = '';

        if($like_count > 0)
        {  
            $itself_like = user_has_already_like_the_micropost($micropost_id);

            $remaining_like_count = $like_count - 3;
            foreach($likers as $liker)
            {
                if(get_session('user_id') !== $liker->id)
                {
                    $output .= '<a href="profile.php?id='.$liker->id.'">'.$liker->pseudo.'</a>, ';
                }
            }
    
            $output = $itself_like ? 'Vous, '.$output : $output;

            if(($like_count == 2 || $like_count == 3) && $output != "")
            {
                $output = trim($output, ', ');
                $arr = explode(',', $output);
                $lastItem = array_pop($arr);
                $output = implode(', ', $arr);
                $output .= ' et '.$lastItem;
            }

            $output = trim($output, ', ');

            switch($like_count)
            {
                case 1:
                    $output .= $itself_like ? ' aimez cela.' : ' aime cela.';
                break;
                
                case 2:
                case 3:
                    $output .= $itself_like ? ' aimez cela.' : ' aiment cela.';
                break;

                case 4:
                    $output .= $itself_like ? ' et 1 autre presonne aimez cela.' : ' et 1 autre personne aiment cela.';
                break;

                default:
                    $output .= $itself_like ? 
                    ' et '.$remaining_like_count.' autre presonne aimez cela.' : 
                    ' et '.$remaining_like_count.' autres personnes aiment cela.';
                break;
            }
        }

        return $output;
    }
}

/**END LIKE BLOCK */

/* // Hash password with blowfish algorithm
    if(!function_exists('bcript_hash_password'))
    {
        function bcript_hash_password($value, $options = array())
        {
            $cost = isset($options['rounds']) ? $options['rounds'] : 10;
            $hash = password_hash($value, PASSWORD_BCRYPT, ['cost' => $cost]);

            if($hash === false){
                throw new Exception("Bcript hashing n'est pas supporté." );
            }
            return $hash;
        }
    }

    // Password verify
    if(!function_exists('bcript_verify_password'))
    {
        function bcript_verify_password($value, $hashedValue)
        {
            return password_verify($value, $hashedValue);
        }
    }
*/

if(!function_exists('find_user_by_id'))
{
    function find_user_by_id($id)
    {
        global $db;

        $q = $db->prepare("SELECT name, pseudo, email, city, country, sex, twitter, github, available_for_hiring, bio, avatar FROM users WHERE id = ?");
        $q->execute([$id]);

        $data = $q->fetch(PDO::FETCH_OBJ);

        $q->closeCursor();
        
        return $data;
    }
}

if(!function_exists('find_code_by_id'))
{
    function find_code_by_id($id)
    {
        global $db;
        $q = $db->prepare('SELECT code FROM codes WHERE id = ?');
        $q->execute([$_GET['id']]);

        $data = $q->fetch(PDO::FETCH_OBJ);
        $q->closeCursor();

        return $data;
    }
}

if(!function_exists('e'))
{
    function e($string)
    {
        if($string)
        {
            return htmlspecialchars($string);
        }
    }
}

//Check if an user is connect
if(!function_exists('is_logged_in'))
{
    function is_logged_in()
    {
        return isset($_SESSION['user_id']) || isset($_SESSION['pseudo']);
    }
}


if(!function_exists('not_empty'))
{
    function not_empty($fields = [])
    {
        if(count($fields) != 0)
        {
            foreach($fields as $field)
            {
                if(empty($_POST[$field]) || trim($_POST[$field]) == "")
                {
                    return false;
                }
            }
            return true;
        }
    }
}

if(!function_exists('is_already_in_use'))
{
    function is_already_in_use($field, $value, $table)
    {
        global $db;

        $q = $db->prepare("SELECT id FROM $table WHERE $field = ?");
        $q->execute([$value]);
        $count = $q->rowCount();

        $q->closeCursor();

        return $count;
    }
}

if(!function_exists('set_flash'))
{
    function set_flash($message, $type = 'info')
    {
        $_SESSION['notification']['message'] = $message;
        $_SESSION['notification']['type'] = $type;
    }
}

// Redirect users on page where they want open
if(!function_exists('redirect_intent_or'))
{
    function redirect_intent_or($default_url)
    {
        if($_SESSION['forwarding_url'])
        {
            $url = $_SESSION['forwarding_url'];
        }else{
            $url = $default_url;
        }
        $_SESSION['forwarding_url'] = null;
        redirect($url);
    }
}

if(!function_exists('redirect'))
{
    function redirect($page)
    {
        header('Location: ' .$page);
        exit();
    }
}

if(!function_exists('save_input_data'))
{
    function save_input_data()
    {
        foreach($_POST as $key => $value)
        {
            if(strpos($key, "password") === false)
            {
                $_SESSION['input'][$key] = $value;
            }
        }
    }
}

//Get avatar URL on gravatar
if(!function_exists('get_avatar_url'))
{
    function get_avatar_url($email, $size = 25)
    {
        return "http://gravatar.com/avatar/".md5(strtolower(trim(e($email))))."?s=".$size.'&d=wavatar';
    }
}

//Show avatar
if(!function_exists('show_avatar'))
{
    function show_avatar($id, $width = 25, $height = 25)
    {
        global $db;

        $q = $db->query("SELECT id, pseudo, email, avatar FROM users WHERE id = $id");
        $data = $q->fetch(PDO::FETCH_OBJ);

        $src = "Users/avatar/".$id."/".$data->avatar;
        $img = '<img src="'.$src.'" alt="Image de profil de '.e($data->pseudo).'" class="img-circle" width="'.$width.'" height="'.$height.'"/>';

        $gravatar = '<img src="'.get_avatar_url($data->email, 100).'" alt="Image de profil de '.e($data->pseudo).'" class="img-circle" width="'.$width.'" height="'.$height.'"/>';

        return $data->avatar ? $img : $gravatar;
    }
}

if(!function_exists('get_input'))
{
    function get_input($key)
    {
        return !empty($_SESSION['input'][$key]) ? e($_SESSION['input'][$key]): null;
    }
}

if(!function_exists('cleer_input_data'))
{
    function cleer_input_data()
    {
        if(isset($_SESSION['input']))
        {
            $_SESSION['input'] = [];
        }
    }
}

//Gere l'état actif de nos différents liens
if(!function_exists('set_active'))
{
    function set_active($file, $class = 'active')
    {
        $page = array_pop(explode('/', $_SERVER['SCRIPT_NAME']));
        if($page == $file.'php')
        {
            return $class;
        }else{
            return "";
        }
    }
}

//fonction qui indique en temps réel la date de publication

if(!function_exists('time_ago'))
{
    function time_ago($timestamp)
    {
        $time_ago = strtotime($timestamp);
        $current_time = time();
        $difference_time = $current_time - $time_ago;

        $seconds = $difference_time;
        $minutes = round($seconds / 60); // value 60 is seconds
        $hours   = round($seconds / 3600); // value 3600 is 60 minutes * 60 seconds
        $days    = round($seconds / 86400); // 86400 == 24*60*60
        // $weeks   = round($seconds / 604800); // 604800 == 7*24*60*60
        // $months  = round($seconds / 2629440); // ((365+365+365+365+366)/5/12)*24*60*60
        // $years   = round($seconds / 31553280); // (365+365+365+365+366)/5*24*60*60

        if($seconds <= 60)
        {
            return strtoupper('à')." l'instant";
        }
        else if($minutes <= 60)
        {
            if($minutes == 1)
            {
                return "Il y a ".$minutes."minute";
            }else{
                return "Il y a ".$minutes."minutes";
            }
        }
        else if($hours <= 24)
        {
            if($hours == 1)
            {
                return "Il y a ".$hours."heure";
            }else{
                return "Il y a ".$hours."heures";
            }
        }
        else if($days <= 7)
        {
            if($days == 1)
            {
                return "Il y a ".$days."jour";
            }else{
                return "Il y a ".$days."jours";
            }
        }else
        {
            return $timestamp;
        }

    }
}