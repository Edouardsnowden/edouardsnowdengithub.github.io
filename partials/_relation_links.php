
<?php if(display_relation_link($_GET['id']) == CANCEL_RELATION_LINK): ?>

    <a href="delete_friend.php?id=<?= $_GET['id'] ?>" class="btn btn-default float-start border">Annuler</a>

<?php elseif(display_relation_link($_GET['id']) == ACCEPT_REJECT_RELATION_LINK): ?>

    <a href="accept_friend.php?id=<?= $_GET['id'] ?>" class="btn btn-primary float-start"><i class="fas fa-user-friends"></i> Accepter</a>
    <a href="delete_friend.php?id=<?= $_GET['id'] ?>" class="btn btn-warning float-start"><i class="fas fa-running"></i> Decliner</a>

<?php elseif(display_relation_link($_GET['id']) == DELETE_RELATION_LINK): ?>

    <a href="delete_friend.php?id=<?= $_GET['id'] ?>" class="btn btn-danger float-start border"><i class="fas fa-running"></i> Retirer</a>

<?php elseif(display_relation_link($_GET['id']) == ADD_RELATION_LINK): ?>
    
    <a href="add_friend.php?id=<?= $_GET['id'] ?>" class="btn btn-primary float-start border"><i class="fa fa-plus"></i> Amis</a>

<?php endif ?>