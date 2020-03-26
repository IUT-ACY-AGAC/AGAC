<?php

include_once "mvc.php";
include_once "db/models/Lieu.php";
include_once "db/models/Cadastre.php";
include_once "auth.php";

$routes = [
    "GET" => [
        "" => function ()
        {
            view("main");
        },

        "connexion" => function ()
        {
            view("login");
        },

        "deconnexion" => function ()
        {
            deconnexion();

            redirect(url("/"));
        },

        "recherche" => function ()
        {
            if (!isset($_GET["q"]))
            {
                abort(400);
                return;
            }

            json(Lieu::recherche($_GET["q"]));
        },

        "exec" => function ()
        {
            $cad1 = @Cadastre::retrieveByPK($_GET["id1"]);
            $cad2 = @Cadastre::retrieveByPK($_GET["id2"]);

            if (!$cad1 || !$cad2)
            {
                echo "Il faut spécifier les cadastres.";
                abort(400);
                return;
            }

            if ($cad1->idlieu !== $cad2->idlieu)
            {
                echo "Les lieux ne correspondent pas.";
                abort(400);
                return;
            }

            $url1 = UPLOAD_PATH . $cad1->img;
            $url2 = UPLOAD_PATH . $cad2->img;


            $res = binget('"' . $url1 . '" "' . $url2 . '"');
            if ($res)
            {
                header("Content-Type: application/octet-stream");
                header("Content-Encoding: gzip");
                ob_start("gzencode");
                echo $res;
            }
            else
            {
                abort(400);
                return;
            }
        },

        "erreur/403" => function ()
        {
            view("errors/403");
        },

        "lieux" => function ()
        {
            $params = ["lieux" => Lieu::all()];
            if (isset($_GET["modif"]))
            {
                $cad = @Lieu::retrieveByPK($_GET["modif"]);
                if ($cad)
                {
                    $params["modif"] = $cad;
                }
            }
            view("lieux", $params);
        },

        "cadastres" => function ()
        {
            $params = ["cadastres" => Cadastre::all(), "lieux" => Lieu::all()];
            if (isset($_GET["modif"]))
            {
                $cad = @Cadastre::retrieveByPK($_GET["modif"]);
                if ($cad)
                {
                    $params["modif"] = $cad;
                }
            }
            view("cadastres", $params);
        },

        "cadastres/voir" => function ()
        {
            $cad = @Cadastre::retrieveByPK($_GET["id"]);

            if (!$cad)
            {
                abort(400);
                return;
            }

            header("Content-type: image");
            readfile(UPLOAD_PATH . $cad->img);
        },

        "cadastres/liste" => function ()
        {
            $lieu = @Lieu::retrieveByPK($_GET["id"]);

            if (!$lieu)
            {
                abort(400);
                return;
            }
            $vals = array_map(function (Cadastre $c)
            {
                return $c->getFields("idcadastre", "titrecadastre");
            }, $lieu->cadastres());
            usort(
                $vals,
                function ($a, $b)
                {
                    return $a["titrecadastre"] <=> $b["titrecadastre"];
                });
            json($vals);
        }
    ],

    "POST" => [
        "connexion" => function ()
        {
            if (!isset($_POST["user"]) || !isset($_POST["password"]))
            {
                redirect(url("/connexion"));
                return;
            }

            connexion($_POST["user"], $_POST["password"]);
        },

        "lieux/supprimer" => function ()
        {
            if (!est_connecte())
            {
                redirect(url("/connexion"));
                return;
            }

            $lieu = @Lieu::retrieveByPK($_POST["id"]);

            if ($lieu)
            {
                $lieu->delete();
            }

            redirect(url("/lieux"));
        },

        "lieux/ajouter" => function ()
        {
            if (!est_connecte())
            {
                redirect(url("/connexion"));
                return;
            }

            $verif = true;
            $orig = null;

            if (isset($_POST["modif"]))
            {
                $orig = @Lieu::retrieveByPK($_POST["modif"]);

                if (!$orig)
                {
                    abort(500);
                    return;
                }

                if ($orig->nomlieu === $_POST["nom"])
                {
                    $verif = false;
                }
            }

            if ($verif && !empty(Lieu::retrieveByField("nomlieu", $_POST["nom"])))
            {
                flash("lieux_ajouter_err", "doublon");
            }
            else
            {
                if ($orig)
                {
                    $orig->nomlieu = $_POST["nom"];
                    $orig->localisation = $_POST["loc"];
                    $orig->save();
                    flash("lieux_ajouter_err", "succes");
                }
                else
                {
                    flash("lieux_ajouter_err", new Lieu([
                        "nomlieu" => $_POST["nom"],
                        "localisation" => $_POST["loc"]
                    ], DataModel::LOAD_NEW) ? "succes" : "upload");
                }
            }

            redirect(url("/lieux"));
        },

        "cadastres/ajouter" => function ()
        {
            if (!est_connecte())
            {
                redirect(url("/connexion"));
                return;
            }

            $verif = true;
            $orig = null;

            if (isset($_POST["modif"]))
            {
                $orig = @Cadastre::retrieveByPK($_POST["modif"]);

                if (!$orig)
                {
                    abort(500);
                    return;
                }

                if ($orig->titrecadastre === $_POST["nom"])
                {
                    $verif = false;
                }
            }

            if ($verif && !empty(Cadastre::retrieveByField("titrecadastre", $_POST["nom"])))
            {
                flash("cadastres_ajouter_err", "doublon");
            }
            else
            {
                $up_succes = null;

                if (isset($_FILES[1]["tmp_name"]) && $_FILES[1]["tmp_name"])
                {
                    $tmp = $_FILES[1]["tmp_name"];
                    $verif = getimagesize($tmp);

                    if (!preg_match("#^(image/)[^\s\n<]+$#i", $verif["mime"]))
                    {
                        flash("cadastres_ajouter_err", "upload");
                    }

                    $dest = uniqid(rand(), true) . ".dat";

                    $up_succes = move_uploaded_file($tmp, UPLOAD_PATH . $dest);
                }

                if ($up_succes === false)
                {
                    flash("cadastres_ajouter_err", "upload");
                }
                else
                {
                    if ($orig)
                    {
                        $orig->titrecadastre = $_POST["nom"];
                        $orig->descriptioncadastre = $_POST["description"];
                        $orig->legendecadastre = $_POST["legende"];

                        if ($up_succes === true)
                        {
                            $orig->img = $dest;
                        }

                        $orig->save();
                        flash("lieux_ajouter_err", "succes");
                    }
                    else
                    {
                        if ($up_succes === null)
                        {
                            flash("cadastres_ajouter_err", "upload");
                        }
                        else
                        {
                            flash("cadastres_ajouter_err", @new Cadastre([
                                "idlieu" => $_POST["loc"],
                                "titrecadastre" => $_POST["nom"],
                                "descriptioncadastre" => $_POST["description"],
                                "legendecadastre" => $_POST["legende"],
                                "img" => $dest
                            ], DataModel::LOAD_NEW) ? "succes" : "upload");
                        }
                    }
                }
            }

            redirect(url("/cadastres") . ($orig ? "?modif=" . $orig->idcadastre : ""));
        },

        "cadastres/supprimer" => function ()
        {
            if (!est_connecte())
            {
                redirect(url("/connexion"));
                return;
            }

            $cad = @Cadastre::retrieveByPK($_POST["id"]);

            if ($cad)
            {
                $cad->delete();
            }

            redirect(url("/cadastres"));
        },
    ]
];

function abort(int $code, $input = [])
{
    http_response_code($code);
    return view("errors/$code", $input);
}

function get_current_url()
{
    return explode('?', $_SERVER['REQUEST_URI'], 2)[0];
}

function get_current_route()
{
    return @$_GET["route"] ?? "";
}

function get_site_root()
{
    return "//" . $_SERVER['HTTP_HOST'] .
        preg_replace("#/" . preg_quote(get_current_route()) . "$#i", "", get_current_url());
}

function url($url)
{
    if ($url[0] != "/")
        $url = "/" . $url;
    return get_site_root() . $url;
}

function asset($name)
{
    return url("assets/$name");
}

function handle_route()
{
    global $routes;

    if (array_key_exists($_SERVER['REQUEST_METHOD'], $routes))
    {
        $meth = $routes[$_SERVER['REQUEST_METHOD']];

        $route = get_current_route();

        if (array_key_exists($route, $meth))
        {
            http_response_code(200);

            return $meth[$route]();
        }

        return abort(404); // page non trouvée
    }

    return abort(405); // méthode non autorisée
}