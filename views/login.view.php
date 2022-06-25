<?php
    $title = 'Connexion';
    require_once('./partials/_header.php');
    require_once('./partials/_nav.php');
?>
<body>

    <!-- Begin page content -->
    <div class="main-content">

        <div class="p-4 container-fluid">
            <h1 class="lead">Connexion</h1>

            <?php include('partials/_errors.php'); ?>
            <?php include('partials/_flash.php'); ?>

            <form data-parsley-validate method="POST" class="bg-light p-3 rounded-3 col-md-6">

                <!-- Pseudo ou Adresse email : -->
                <div class="form-group">
                    <label class="control-label" for="identifiant">Pseudo ou Adresse email :</label>
                    <input type="text" class="form-control" value="<?= get_input('identifiant') ?>" name="identifiant" id="identifiant" required/>
                </div><br>
                <!-- Password field -->
                <div class="form-group">
                    <label class="control-label" for="password">Mot de passe:</label>
                    <input type="password" class="form-control" name="password" id="password" required/>
                </div><br>
                <!-- Password field -->
                <div class="form-group">
                    <label class="control-label" for="remember_me">
                        <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me">
                        Garder ma session active
                    </label>
                </div><br>

                <input type="submit" class="btn btn-primary" value="Connexion" name="login"/>

            </form>
        </div>

    </div>


    <?php require('./partials/_footer.php'); ?>
