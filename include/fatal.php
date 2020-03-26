<?php

include_once "mvc.php";

function readable_error_type($error_code)
{
    if ($error_code < 0)
        return $error_code;

    $constants = array();
    foreach (get_defined_constants() as $key => $value)
    {
        if (strpos($key, 'E_') === 0 && ($value <= $error_code) && ($value & $error_code))
        {
            $constants[] = $key;
        }
    }

    return implode(' | ', $constants);
}

function show_error()
{
    ?>
    <div class="container-fluid">
        <div class="w-100 text-center">
            <h2 class="pb-3">Une erreur est survenue pendant le traitement de la requête.</h2>

            <h4>Détails :</h4>
            <b>Code d'erreur :</b> <?= readable_error_type(get("error")[0]) ?><br/>
            <b>Message :</b><br/>
            <pre class="card my-1 p-2 text-left d-inline-block"><?= trim(get("error")[1]) ?></pre>
            <br/>
            <b>Fichier :</b> <?= get("error")[2] ?><br/>
            <b>Ligne :</b> <?= get("error")[3] ?><br/>


            <?php include VIEW_PATH."widgets/backhome.php"; ?>
        </div>
    </div>
    <?php
}

register_shutdown_function("crash");

function crash()
{
    $error = error_get_last();

    if ($error && ($error['type'] & (E_ERROR | E_USER_ERROR | E_PARSE | E_CORE_ERROR |
                E_COMPILE_ERROR | E_RECOVERABLE_ERROR)))
    {
        handler($error['type'], $error['message'], $error['file'], $error['line']);
    }
}

set_error_handler("handler");

function handler($errno, $errstr, $errfile, $errline)
{
    if (error_reporting() == 0)
        return;

    set("error", [$errno, $errstr, $errfile, $errline]);

    global $view_loaded;
    if ($view_loaded)
    {
        show_error();
    }
    else if (function_exists("content"))
    {
        ob_start();
        show_error();
        set("content", ob_get_clean());
        include_once "views/app.php";
    }
    else
        return abort(500);
}