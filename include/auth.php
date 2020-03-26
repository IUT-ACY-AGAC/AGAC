<?php

include_once "db/models/Compte.php";

function connexion($nom, $mdp)
{
    $cpt = Compte::recherche($nom);

    if ($cpt === null || !password_verify($mdp, $cpt->mdpcompte))
    {
        flash("login_err", "incorrect");
        redirect(url("/connexion"));
        return;
    }

    $_SESSION["idcompte"] = $cpt->idcompte;
    $_SESSION["nomcompte"] = $cpt->nomcompte;

    redirect(url("/"));
}

function deconnexion()
{
    unset($_SESSION["idcompte"]);
    unset($_SESSION["nomcommpte"]);
}

function est_connecte()
{
    return isset($_SESSION["idcompte"]);
}