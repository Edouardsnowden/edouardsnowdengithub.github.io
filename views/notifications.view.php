<?php $title = "Notifications"; ?>
<?php include('partials/_header.php'); ?>

<div id="main-content">
    <div class="container">
        <h1 class="lead">Vos notifications</h1>

        <ul class="list-group">
            <?php foreach ($notifications as $notification) : ?>
                <li class="list-group-item
                    <?= $notification->seen == '0' ? 'not_seen' : '' ?>">
                    <?php require("partials/notifications/{$notification->name}.php"); ?>
                </li>
            <?php endforeach; ?>
        </ul><br>

        <div class="col-md-6 offset-md-3" id="pagination"><?= $pagination ?></div>
    </div>
</div>

<?php include('partials/_footer.php'); ?>