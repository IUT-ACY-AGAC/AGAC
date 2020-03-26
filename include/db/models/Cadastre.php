<?php

class Cadastre extends DataModel
{
    protected static $pkColumn = 'idcadastre';
    protected static $columns = [
        "idcadastre",
        "idlieu",
        "titrecadastre",
        "descriptioncadastre",
        "legendecadastre",
        "img"
    ];

    public function delete()
    {
        @unlink(UPLOAD_PATH . $this->img);

        parent::delete();
    }
}