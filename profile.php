<?php
session_start();

require("includes/init.php");
include('filters/auth_filter.php');


if (!empty($_GET['id'])) {
    //Recuperer les infos sur l'user en bdd en utilisant son id
    $user = find_user_by_id($_GET['id']);

    if (!$user) {
        redirect('index.php');
    } else {
        $q = $db->prepare("SELECT U.id user_id, U.pseudo, U.email, U.avatar,
                                    M.id m_id, M.content, M.like_count, M.created_at
                            FROM users U, microposts M, friends F
                            WHERE M.user_id = U.id

                            AND

                            CASE
                            WHEN F.user_id1 = :user_id
                            THEN F.user_id2 = M.user_id

                            WHEN F.user_id2 = :user_id
                            THEN F.user_id1 = M.user_id
                            END

                            AND F.status > 0
                            ORDER BY M.id DESC");

        $q->execute([
            'user_id' => $_GET['id']
        ]);

        $microposts = $q->fetchAll(PDO::FETCH_OBJ);
    }
} else {
    redirect('profile.php?id=' . get_session('user_id'));
}

require("views/profile.view.php");