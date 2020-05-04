<?php
use NNV\RestCountries;
include_once 'IBuscador.php';

class Continentes implements IBuscador
{
    public $nombreContinente;
    private $restCountries;
    
    public function __construct($nombre='Americans')
    {
        $this->nombreContinente=$nombre;
        $this->restCountries = new RestCountries;
    }

    public function BuscarPorCapital($capital)
    {
        return $this->restCountries->byCapitalCity($capital);
    }

    public function BuscarPorPais($pais)
    {
        return $this->restCountries->byName($pais);
    }

    public function BuscarPorIdioma($idioma)
    {
        return $this->restCountries->byLanguage($idioma);
    }

    public function BuscarPorContinente($continente)
    {
        return $this->restCountries->byRegion($continente);
    }
    
}