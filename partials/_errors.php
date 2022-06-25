<?php

if(isset($errors) && count($errors) != 0)
{
        echo '<div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"></span></button>';
            foreach($errors as $error)
            {
                echo $error .'<br/>';
            }
        echo '</div>';
}