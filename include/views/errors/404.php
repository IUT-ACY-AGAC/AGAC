<?php

set("title", "Page non trouvée");

function content()
{
    ?>
    <div class="container-fluid">
        <div class="w-100 text-center">
            <img class="pb-3" src="https://media.giphy.com/media/hEc4k5pN17GZq/giphy.gif"/>

            <h2>La page demandée n'a pas pu être trouvée.</h2>

            <?php include VIEW_PATH."widgets/backhome.php"; ?>
        </div>
    </div>
    <?php
}