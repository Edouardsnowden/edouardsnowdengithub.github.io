<?php ?>
    <article class="media statut-media card" id="micropost<?= $micropost->m_id ?>">
        <div class="pull-left">
            <?= show_avatar($micropost->user_id) ?>
        </div>
        <div class="media-body">
            <h4 class="media-heading"><?= $micropost->pseudo ?></h4>
            <p><i class="fa fa-clock-o"></i>
                <span class="timeago" title="<?= e($micropost->created_at) ?>">
                <?= time_ago(e($micropost->created_at)); ?>
                </span>&nbsp;

                <?php if($micropost->user_id == get_session('user_id')): ?>

                    <a href="delete_micropost.php?id=<?= $micropost->m_id ?>" data-confirm="Voulez-vous vraiment supprimer cette publication ?">
                        <i class="fa fa-trash"></i> 
                            Supprimer
                    </a>

                <?php endif; ?>

            </p>
            <?= nl2br(replace_links(e($micropost->content))) ?>
            <hr>
            <p>
                <?php if(user_has_already_like_the_micropost($micropost->m_id)): ?>
                    <a id="unlike<?= $micropost->m_id ?>" data-action="unlike" class="like" href="unlike_micropost.php?id=<?= $micropost->m_id ?>">Je n'aime plus</a>
                <?php else: ?>
                    <a id="like<?= $micropost->m_id ?>" data-action="like" class="like" href="like_micropost.php?id=<?= $micropost->m_id ?>">J'aime</a>
                <?php endif ?>
            </p>
            <div id="likers_<?=$micropost->m_id ?>">
                <?= get_likers_text($micropost->m_id); ?>
            </div>
        </div>
    </article>