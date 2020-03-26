<?php

class Compte extends DataModel
{
    protected static $pkColumn = 'idcompte';
    protected static $columns = [
        "idcompte",
        "nomcompte",
        "mdpcompte"
    ];

    public static function recherche($nom)
    {
        $nom = trim(strtolower($nom));

        foreach (self::all() as $c)
        {
            if (strtolower($c->nomcompte) == $nom)
            {
                return $c;
            }
        }

        return null;
    }
}