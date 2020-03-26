<?php

class Lieu extends DataModel
{
    protected static $pkColumn = 'idlieu';
    protected static $columns = [
        "idlieu",
        "nomlieu",
        "localisation"
    ];

    public static function recherche($q)
    {
        function sanitize($s) {
            return preg_replace('/\s+/', ' ', str_replace("-", " ", $s));
        }

        $res = self::all();

        if ($q)
        {
            $res = array_filter($res, function (Lieu $x) use ($q)
            {
                return stripos(sanitize($x->nomlieu), sanitize($q)) !== false;
            });
        }

        usort($res, function($a, $b) {
            return $a->nomlieu <=> $b->nomlieu;
        });

        return $res;
    }

    public function cadastres()
    {
        return Cadastre::retrieveByField("idlieu", $this->idlieu);
    }

    public function delete()
    {
        foreach($this->cadastres() as $c)
        {
            $c->delete();
        }

        parent::delete();
    }
}