<?php
session_start();

require('../config/database.php');
require('../includes/functions.php');

extract($_POST);

$q = $db->prepare('SELECT id, name, pseudo, email, avatar FROM users
                    WHERE pseudo LIKE :query OR email LIKE :query OR avatar LIKE :query LIMIT 5');

$q->execute([
    'query' => '%'. $query .'%'
]);

$users = $q->fetchAll(PDO::FETCH_OBJ);

if(count($users) > 0){
    foreach($users as $user)
    {
    ?>
        <div class="display-box-user">
            <a href="profile.php?id=<?= $user->id ?>">
                <?= show_avatar($user->id) ?>&nbsp; <?= e($user->name). ' '. e($user->pseudo) ?> <br> <?= e($user->email) ?>
            </a>
        </div>
    <?php
    }
}else{
    echo '<div class="display-box-user"><p>Aucun utilisateur trouver !</p></div>';
}
?>