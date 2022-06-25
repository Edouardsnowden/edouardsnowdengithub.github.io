<?php
    $title = 'Inscription';
    require_once('./partials/_header.php');
    require_once('./partials/_nav.php');
?>

    <!-- Begin page content -->
    <div class="main-content">

        <div class="p-4 container-fluid">
            <h1 class="lead">Devenez dès à présent membre</h1>

            <?php include('partials/_errors.php'); ?>

            <form data-parsley-validate method="POST" class="bg-light p-3 rounded-3 col-md-6">

                <!-- Name field -->
                <div class="form-group">
                    <label class="control-label" for="name">Nom:</label>
                    <input type="text" class="form-control" value="<?= get_input('name') ?>" name="name" id="name" required/>
                </div><br>
                <!-- Pseudo field -->
                <div class="form-group">
                    <label class="control-label" for="pseudo">Pseudo:</label>
                    <input type="text" class="form-control" value="<?= get_input('pseudo') ?>" name="pseudo" id="pseudo" required data-parsley-minlength="3" data-parsley-trigger="keypress"/>
                </div><br>
                <!-- Email field -->
                <div class="form-group">
                    <label class="control-label" for="email">Adresse Email:</label>
                    <input type="email" class="form-control" value="<?= get_input('email') ?>" name="email" id="email" required data-parsley-trigger="keypress"/>
                </div><br>
                <!-- Password field -->
                <div class="form-group">
                    <label class="control-label" for="password">Mot de passe:</label>
                    <input type="password" class="form-control" name="password" id="password" required/>
                </div><br>
                <!-- Password Confirmation field -->
                <div class="form-group">
                    <label class="control-label" for="password_confirm">Confirmer votre mot de passe:</label>
                    <input type="password" class="form-control" name="password_confirm" id="password_confirm" required data-parsley-equalto="#password"/>
                </div><br>

                <input type="submit" class="btn btn-primary" value="Inscription" name="register"/>

            </form>
        </div>

    </div>


    <?php require('./partials/_footer.php'); ?>
