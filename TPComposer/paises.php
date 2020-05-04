<?php
include_once 'continentes.php';

class Paises extends Continentes 
{
    public $continente;
    public $nombre;
    public $idioma;
    public $subregion;
    public $capital;
    public $gentilicio;

    public function __construct($contin, $subreg, $lenguaje, $cap, $pais, $gentilicio)
    {
        $this->continente=new Continentes($contin);
        $this->subregion=$subreg;
        $this->idioma=$lenguaje;
        $this->capital=$cap;
        $this->nombre=$pais;
        $this->gentilicio=$gentilicio;
    }
}