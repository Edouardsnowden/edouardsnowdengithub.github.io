

    <header>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-md navbar-light fixed-top bg-blank border">
        <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><?= WEBSITE_NAME ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse col-md-6" id="navbarCollapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="list_users.php">Liste des Utilisateurs</a>
                </li>
                <li class="nav-item">
                    <input type="search" name="searchbox" id="searchbox" class="form-control" placeholder="Rechercher un utilisateur">
                    <div id="display-results">
                    </div>
                </li>&nbsp;
                <li><i id="spinner" class="fa-solid fa-spinner fa-spin" style="display: none;"></i></li>
            </ul>
            <ul class="nav navbar-nav">

            <?php if(is_logged_in()): ?>
                
                <li class="<?= $notifications_count > 0 ? 'have_notifs' : 'notifs' ?>">
                    <a href="notifications.php"><i class="fa fa-bell"></i>
                        <?= $notifications_count > 0 ? "($notifications_count)" : ''; ?>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= show_avatar(get_session('user_id')) ?>
                    </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDarkDropdownMenuLink">
                    <li class="nav-item <?= set_active('profile'); ?>">
                        <a class="nav-link" href="profile.php?id=<?= get_session('user_id') ?>"><?= $menu["mon_profil"][$_SESSION['locale']] ?></a>
                    </li>
                    <li class="nav-item <?= set_active('edit_user'); ?>">
                        <a class="nav-link" href="edit_user.php?id=<?= get_session('user_id') ?>"><?= $menu["editer_profil"][$_SESSION['locale']] ?></a>
                    </li>
                    <li>
                        <a class="nav-link <?= set_active('share_code'); ?> " href="share_code.php">Code</a>
                    </li>
                    <li class="nav-item <?= set_active('change_password'); ?>">
                        <a class="nav-link" href="change_password.php"><?= $menu["change_password"][$_SESSION['locale']] ?></a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="nav-link" href="logout.php"><?= $menu["deconnexion"][$_SESSION['locale']] ?></a>
                    </li>
                </ul>
                </li>

            <?php else: ?>

            <li class="nav-item <?= set_active('login'); ?>">
                <a class="nav-link" href="login.php"><?= $menu["connexion"][$_SESSION['locale']] ?></a>
            </li>
            <li class="nav-item <?= set_active('register'); ?>">
                <a class="nav-link" href="register.php"><?= $menu["inscription"][$_SESSION['locale']] ?></a>
            </li>

            <?php endif; ?>

            </ul>
        </div>
        </div>
    </nav>
    </header>