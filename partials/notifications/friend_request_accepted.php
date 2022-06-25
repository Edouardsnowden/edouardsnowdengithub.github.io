<a href="profile.php?id=<?= $notification->user_id ?>">
    <?= show_avatar($notification->user_id) ?>
        <?= e($notification->pseudo) ?>
</a>
a accepté votre demande d'amitié 
    <span class="timeago" title="<?= time_ago($notification->created_at) ?>"><?= time_ago($notification->created_at) ?></span>.