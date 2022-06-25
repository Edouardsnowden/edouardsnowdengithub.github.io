<?php

    $title = 'Acceil';

    require_once('./partials/_header.php');
    require_once('./partials/_nav.php');
    require_once('partials/_flash.php');
?>

    <!-- Begin page content -->
    <div class="main-content">

        <div class="p-4 container-fluid">
            <div class="bg-light mt-0 py-5 p-5 rounded-3">
                <h1 class="mt-0"><?= WEBSITE_NAME ?>?</h1>
                <?= $long_text['accueil_intro'][$_SESSION['locale']]; ?>
            </div>
        </div>

    </div>


    <?php require('./partials/_footer.php'); ?>
