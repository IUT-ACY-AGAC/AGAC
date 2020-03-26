<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "include/db/conn.php";

if (isset($_POST["mdp"]))
{
    echo "Création du schéma... ";
    DataModel::getConnection()->exec("CREATE SCHEMA agac;");
    echo "OK<br/>";

    echo "Création de la table 'cadastre'... ";
    DataModel::getConnection()->exec(<<<EOF
CREATE SEQUENCE agac.cadastre_idcadastre_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE agac.cadastre
(
    idcadastre integer NOT NULL DEFAULT nextval('agac.cadastre_idcadastre_seq'::regclass),
    idlieu integer NOT NULL,
    titrecadastre character varying(100),
    descriptioncadastre text,
    legendecadastre character varying(200),
    img text,
    CONSTRAINT cadastre_pkey PRIMARY KEY (idcadastre)
);
EOF
    );
    echo "OK<br/>";

    echo "Création de la table 'compte'... ";
    DataModel::getConnection()->exec(<<<EOF
CREATE SEQUENCE agac.compte_idcompte_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE agac.compte
(
    idcompte integer NOT NULL DEFAULT nextval('agac.compte_idcompte_seq'::regclass),
    nomcompte character varying(256) NOT NULL,
    mdpcompte character varying(256) NOT NULL,
    CONSTRAINT compte_pkey PRIMARY KEY (idcompte)
);
EOF
    );
    echo "OK<br/>";

    echo "Création de la table 'lieu'... ";
    DataModel::getConnection()->exec(<<<EOF
CREATE SEQUENCE agac.lieu_idlieu_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

CREATE TABLE agac.lieu
(
    idlieu integer NOT NULL DEFAULT nextval('agac.lieu_idlieu_seq'::regclass),
    nomlieu character varying(100),
    localisation character varying(100),
    CONSTRAINT lieu_pkey PRIMARY KEY (idlieu)
);

EOF
    );
    echo "OK<br/>";

    echo "Création du compte administrateur... ";
    $mdp = password_hash($_POST["mdp"], PASSWORD_DEFAULT);
    DataModel::sql("INSERT INTO agac.compte(nomcompte, mdpcompte) VALUES ('admin', '$mdp');");
    echo "OK<br/>";

    echo "<br/><br/>";

    echo "Installation terminée. Vous pouvez supprimer le fichier 'install.php'.<br/>";
    echo '<a href="index.php">Accéder à la page d\'accueil</a>';
}
else
{
    ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="assets/logo_square.png"/>

        <title>AGAC – Installation</title>

        <link rel="stylesheet"
              href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css"
              integrity="sha256-YLGeXaapI0/5IgZopewRJcFXomhRMlYYjugPLSyNjTY=" crossorigin="anonymous"/>
    </head>
    <body>
    <div class="container-fluid">
        <div class="card text-center mx-auto mt-5" style="width: 400px">
            <div class="card-header">
                <h4 class="mb-3">Installation</h4>
                Pensez bien à renseigner les informations de connexion de la base dans include/db/conn.php
            </div>
            <div class="card-body">
                <form method="post" action="install.php">
                    <div class="form-group">
                        <label for="mdp">Quel mot de passe souhaitez-vous pour le compte administrateur ('admin') ?</label>
                        <input class="form-control" type="password" name="mdp" required autofocus/>
                    </div>
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary w-100">
                            Démarrer l'installation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </body>
    </html>
    <?php
}