<?php
    $title = 'Page de profil';
    require_once('./partials/_header.php');
    require_once('./partials/_nav.php');
?>

    <!-- Begin page content -->
    <div class="main-content">
        <div class="p-4 container-fluid">
            <h1 style="text-align:center">Liste des utilisateurs</h1><br><br>

            <?php foreach(array_chunk($users, 4) as $user_set): ?>
                <div class="row users">
                    <?php foreach($user_set as $user): ?>
                        <div class="col-md-3 user-block">
                            <a href="profile.php?id=<?= $user->id ?>">
                                <img src="<?= $user->avatar ? "Users/avatar/".$user->id."/".$user->avatar :  get_avatar_url($user->email, 70); ?>" alt="<?= e($user->pseudo); ?>" class="avatar-md">
                            </a>
                            <h4 class="user-block-pseudo">
                                <a href="profile.php?id=<?= $user->id ?>">
                                    <?= e($user->pseudo); ?>
                                </a>
                            </h4>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php endforeach ?>

			<div class="col-md-6 offset-md-3" id="pagination"><?= $pagination ?></div>

        </div>
    </div>


    <?php require('./partials/_footer.php'); ?>
