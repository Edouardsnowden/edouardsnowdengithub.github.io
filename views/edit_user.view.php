<?php
    $title = 'Edition de profil';
    require_once('./partials/_header.php');
    require_once('./partials/_nav.php');
?>

<!-- Begin page content -->
<div class="main-content">
    <div class="p-4 container-fluid">
        <div class="row">
            
            <?php if(!empty($_GET['id']) && $_GET['id'] == get_session('user_id')): ?>

            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Completer mon profil</h4>
                    </div>
                    <div class="card-body">

                        <?php include_once('./partials/_errors.php'); ?>

                    <form data-parsley-validate action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nom<span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="John" value="<?= get_input('name')? : e($user->name) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city">Ville<span class="text-danger">*</span></label>
                                    <input type="text" name="city" id="city" class="form-control" value="<?= get_input('city')? : e($user->city) ?>" required>
                                </div>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="avatar">Changer mon avatar</label><br>
                                    <input type="file" name="avatar" id="avatar">
                                </div>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Pays<span class="text-danger">*</span></label>
                                    <input type="text" name="country" id="country" class="form-control" value="<?= get_input('country')? : e($user->country) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sex">Sexe<span class="text-danger">*</span></label>
                                    <select name="sex" id="sex" class="form-control" required>
                                        <option value="H" <?= $user->sex == "H" ? "selected" : ""; ?>>Homme</option>
                                        <option value="F" <?= $user->sex == "F" ? "selected" : ""; ?>>Femme</option>
                                    </select>
                                </div>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="twitter">twitter</label>
                                    <input type="text" name="twitter" id="twitter" class="form-control" value="<?= get_input('twitter')? : e($user->twitter) ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="github">github</label>
                                    <input type="text" name="git" id="github" class="form-control" value="<?= get_input('github')? : e($user->github) ?>">
                                </div>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="available_for_hiring">
                                    <input type="checkbox" name="available_for_hiring" id="available_for_hiring" <?= $user->available_for_hiring ? "checked" : "" ?>>
                                    Disponible pour emploi ?
                                </label>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="bio">Bio<span class="text-danger">*</span></label>
                                    <textarea name="bio" id="bio" cols="30" rows="10" class="form-control" placeholder="Je suis un amoureux de la programmation...!" required><?= get_input('bio')? : e($user->bio) ?></textarea>
                                </div>
                            </div>
                        </div><br>

                        <input type="submit" class="btn btn-primary" value="Valider" name="update">

                    </form>

                    </div>
                </div>
            </div>

            <?php endif; ?>

        </div>
    </div>
</div>

<?php require('./partials/_footer.php'); ?>