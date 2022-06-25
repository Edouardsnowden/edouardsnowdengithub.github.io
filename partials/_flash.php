<?php if(isset($_SESSION['notification']['message'])): ?>

        <div class="container-fluid">
            <div class="alert alert-<?= $_SESSION['notification']['type']; ?>">
                <div class="callout"  data-closable="slide-out-right">
                <?= $_SESSION['notification']['message']; ?>
                    <button class="btn-close" aria-label="Close alert" type="button" data-close>
                        <span aria-hidden="true"></span>
                    </button>
                </div>
            </div>
            <?php $_SESSION['notification'] = []; ?>
        </div>
        
<?php endif; ?>