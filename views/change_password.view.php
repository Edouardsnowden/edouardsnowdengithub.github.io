<?php
    $title = 'Modification de mot de passe';
    require_once('./partials/_header.php');
    require_once('./partials/_nav.php');
?>

<!-- Begin page content -->
<div class="main-content">
    <div class="p-4 container-fluid">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Modification de mot de passe</h4>
                    </div>
                    <div class="card-body">

                        <?php include_once('./partials/_errors.php'); ?>

                    <form data-parsley-validate action="" method="post" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="current_password">Mot de passe actuel</label>
                            <input type="password" name="current_password" id="current_password" class="form-control" required>
                        </div><br>
                        <div class="form-group">
                            <label for="niew_password">Nouveau mot de passe</label>
                            <input type="password" name="niew_password" id="niew_password" class="form-control" required data-parsley-minlength="6">
                        </div><br>
                        <div class="form-group">
                            <label for="niew_password_confirmation">Confirmer votre nouveau mot de passe</label>
                            <input type="password" name="niew_password_confirmation" id="niew_password_confirmation" class="form-control" required>
                        </div><br>

                        <input type="submit" class="btn btn-primary" value="Modifier" name="change_password">

                    </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('./partials/_footer.php'); ?>