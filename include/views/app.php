<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AGAC <?= has("title") ? "- " . get("title") : "" ?></title>

    <base href="<?= url("/") ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha256-YLGeXaapI0/5IgZopewRJcFXomhRMlYYjugPLSyNjTY=" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css"
          integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous"/>

    <link rel="stylesheet" type="text/css" href="assets/style.css">

    <?php get("head", ""); ?>
</head>

<body class="<?=get("bodyclasses", "")?>">
<div id="app" class="<?=get("appclasses", "")?>">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="./">
            <img src="assets/logo.png" id="nav-logo" height="40" alt="AGAC"/>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <?php
                $liens = [
                    //"" => ["Accueil", "home"],
                ];

                function is_active($url)
                {
                    return get_current_route() == $url;
                }

                function display_link($url, $n, $icon, $get = "")
                {
                    ?>
                    <li class="nav-item <?= is_active($url) ? "active" : "" ?>">
                        <a class="nav-link" href="<?= $url ?>"><?php if ($icon !== null): ?> <i
                                    class="mr-1 fas fa-<?= $icon ?>"></i> <?php endif; ?> <?= $n ?> <?= is_active($url) ?
                                '<span class="sr-only">(current)</span>' : "" ?>
                        </a>
                    </li>
                    <?php
                }

                foreach ($liens as $url => [$n, $icon])
                    display_link($url, $n, $icon);
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                include_once "include/auth.php";
                if (!est_connecte())
                    display_link("connexion", "Connexion", "sign-in-alt", "?redirect=" . urlencode(get_current_url()));
                else
                {
                    ?>
                    <li class="nav-item border-right border-secondary">
                        <span class="nav-link text-white">
                            Bienvenue, <?= $_SESSION["nomcompte"] ?>
                        </span>
                    </li>
                    <?php
                    display_link("lieux", "Lieux", "map-marker-alt");
                    display_link("cadastres", "Cadastres", "atlas");
                    display_link("deconnexion", "Déconnexion", "sign-out-alt");
                }
                ?>
            </ul>
        </div>
    </nav>
    <?php
    try
    {
        echo get("content");
    }
    catch (Exception $e)
    {
        set("error", [-1, $e->getMessage() . "\n\n" . $e->getTraceAsString(), $e->getFile(), $e->getLine()]);
        show_error();
    }
    ?>
    <div class="footer-spacer"></div>
    <footer class="footer">
        <div class="container text-center">
                <span class="text-muted" style="line-height: normal">
                    Copyright © AGAC
                </span>
        </div>
    </footer>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha256-CjSoeELFOcH0/uxWu6mC/Vlrc1AARqbm/jiiImDGV3s=" crossorigin="anonymous"></script>

<script src="assets/app.js"></script>

<?php get("foot", ""); ?>
</body>

</html>