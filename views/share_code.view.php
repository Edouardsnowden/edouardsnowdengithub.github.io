<?php
    $title = 'Partage de code source';
    require_once('./partials/_header.php');
    require_once('./partials/_nav.php');
?>

    <!-- Begin page content -->
    <div class="main-content">
        <div id="main-content-share-code">
            <form action="" method="post">
                <textarea name="code" id="code" placeholder="Entrer votre code ici...!"><?= e($code); ?></textarea>
                <div class=" btn-group nav-code">
                    <!-- <input type="reset" class="btn btn-danger" id="#code" value="Tout éffacer"> -->
                    <a class="btn btn-danger" href="share_code.php">Tout éffacer</a>
                    <input type="submit" name="save" class="btn btn-success" value="Enregistrer">
                </div>
            </form>
        </div>
    </div>


    
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <span class="text-muted">Place sticky footer content here.</span>
        </div>

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="assets/js/tabby.js"></script>
        <script>
            $("#code").tabby();
            $("#code").height($(window).height() - 50 );
        </script>
    </footer>
  </body>
</html>
    
