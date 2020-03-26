<?php

set("title", "Requête invalide");

function content()
{
    ?>
    <div class="container-fluid">
        <div class="w-100 text-center">
            <img class="pb-3" src="https://media.giphy.com/media/hEc4k5pN17GZq/giphy.gif"/>

            <h2>Le serveur n'a pas compris la requête.</h2>

            <?php include VIEW_PATH."widgets/backhome.php"; ?>
        </div>
    </div>
    <?php
}