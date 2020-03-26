<?php

set("title", "Accès non autorisé");

function content()
{
    ?>
    <div class="container-fluid">
        <div class="w-100 text-center">
            <img class="pb-3" src="http://giphygifs.s3.amazonaws.com/media/njYrp176NQsHS/giphy.gif"/>

            <h2>Vous n'avez pas l'autorisation d'accéder à cette page.</h2>

            <?php include VIEW_PATH."widgets/backhome.php"; ?>
        </div>
    </div>
    <?php
}