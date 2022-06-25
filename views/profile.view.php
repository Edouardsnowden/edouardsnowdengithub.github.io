<?php
    $title = 'Page de profil';
    require_once('./partials/_header.php');
    require_once('./partials/_nav.php');
?>

    <!-- Begin page content -->
    <div class="main-content">
        <div class="p-4 container-fluid">
                <?php include('./partials/_flash.php'); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Profil de <?= e($user->pseudo); ?> (<?=friends_count($_GET['id'])?> ami<?= friends_count($_GET['id']) == 1 ? '' : 's' ?> )</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <?= show_avatar($_GET['id'], 100,100) ?>
                                </div><br>
                                <div class="col-md-6">
                                    <?php if(!empty($_GET['id']) && $_GET['id'] != get_session('user_id')): ?>
                                        <?php include('partials/_relation_links.php') ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <strong><?= e($user->pseudo) ; ?></strong><br>
                                    <a href="mailto:<?= e($user->email); ?>"><?= e($user->email); ?></a><br>
                                    <?=
                                        $user->city && $user->country ? '<i class="fa-solid fa-location-arrow"></i>&nbsp;'.e($user->city).' - '.e($user->country).'<br>' : '';
                                    ?>
                                    <a href="https://www.google.com/maps?q=<?= e($user->city).' '.e($user->country) ?>" target="_blank">Voir sur Google Maps</a>
                                </div>
                                <div class="col-sm-6">
                                    <?=
                                        $user->twitter ? '<i class="fa-brands fa-twitter"></i>&nbsp;<a href="//twitter.com/'.e($user->twitter).'">@'.e($user->twitter).'</a><br>' : '';
                                    ?>
                                    <?=
                                        $user->github ? '<i class="fa-brands fa-github"></i>&nbsp;<a href="//github.com/'.e($user->github).'">'.e($user->github).'</a><br>' : '';
                                    ?>
                                    <?=
                                        $user->sex == "H" ? '<i class="fa-solid fa-person"></i>' : '<i class="fa-solid fa-person-dress"></i>';
                                    ?>
                                    <?=
                                        $user->available_for_hiring ? 'Disponible pour emploi' : 'Non disponible pour emploi';
                                    ?>
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-md-12 card bg-light p-4">
                                    <h5>Petite biographie de <?= e($user->name) ?></h5>
                                    <p>
                                        <?= 
                                            $user->bio ? nl2br(e($user->bio)) : 'Aucune biographie pour le moment...';
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <?php if(!empty($_GET['id']) && $_GET['id'] == get_session('user_id')): ?>
                    <div class="statut-post">
                        <form action="microposts.php" method="post" data-parsley-validate>
                            <div class="form-group">
                                <label class="sr-only" for="content">Statut:</label>
                                <textarea class="form-control" name="content" id="content" rows="4" placeholder="Alors quoi de neuf ?" required data-parsley-minlength="3" data-parsley-maxlength="140"></textarea>
                            </div>
                            <div class="form-group statut-post-submit">
                                <input type="submit" class="btn btn-default btn-sm" name="publish" value="Publier">
                            </div>
                        </form>
                    </div>
                    <?php endif ?>
                    <?php if(current_user_is_friend_with($_GET['id'])): ?>
                    <?php if(count($microposts) != 0): ?>
                        <?php foreach($microposts as $micropost): ?>
                            <?php include('partials/_micropost.php'); ?>
                        <?php endforeach ?>
                    <?php elseif($_GET['id'] != get_session('user_id')): ?>
                            <p>Cet utilisateur n'a encore rien posté pour le moment...</p>
                    <?php endif; ?>
                    <?php else: ?>
                        <p>Vous n'ettes pas ami §</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>


    <?php require('./partials/_footer.php'); ?>
