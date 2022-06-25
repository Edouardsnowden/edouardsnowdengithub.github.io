<?php
    $title = 'Affichage de code source';
    require_once('./partials/_header.php');
    require_once('./partials/_nav.php');
?>

    <!-- Begin page content -->
    <div class="main-content">
        <div id="main-content-show-code" class="col-sm-12">
            <pre class="prettyprint linenums" id="pretty-code"><code><?= e($data->code); ?></code></pre>
            <div class=" btn-group nav-code">
                <a class="btn btn-warning" href="share_code.php?id=<?= $_GET['id'] ?>">Cloner</a>
                <a class="btn btn-primary" href="share_code.php">Nouveau</a>
            </div>
        </div>
    </div>


    
    <?php include_once('./partials/_footer.php'); ?>
    
