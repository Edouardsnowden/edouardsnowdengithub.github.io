<a href="profile.php?id=<?= $notification->user_id ?>">
    <?= show_avatar($notification->user_id) ?>
    <?= e($notification->pseudo) ?>
</a> vous a envoyé une demande d'amitié 
    <span class="timeago" title="<?= time_ago($notification->created_at) ?>">
        <?= time_ago($notification->created_at) ?>
    </span>.

<a class="btn btn-primary" href="accept_friend.php?id=<?= $notification->user_id ?>">
    Accepter
</a>
<a class="btn btn-danger" href="delete_friend.php?id=<?= $notification->user_id ?>">
    Decliner
</a>