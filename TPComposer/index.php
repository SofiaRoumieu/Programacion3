<?php
require_once __DIR__.'/vendor/autoload.php';
include_once 'paises.php';

$pais=new Paises('Americas', 'South', 'es', 'buen', 'Argentina', 'Argentinean');


echo 'Busqueda de Países por Nombre del país';
$listaPaises = $pais->continente->BuscarPorPais($pais->nombre);
foreach ($listaPaises as $value) {
    echo json_Encode($value);
}

echo 'Busqueda de Países por capital';
$listaPaises = $pais->continente->BuscarPorCapital($pais->capital);
foreach ($listaPaises as $value) {
    echo json_Encode($value);
}

echo 'Busqueda de Países por Idioma';
$listaPaises = $pais->continente->BuscarPorIdioma($pais->idioma);
foreach ($listaPaises as $value) {
    echo json_Encode($value);
}
//Busca por Continente
echo 'Busqueda de Países por Continente';
$listaPaises = $pais->continente->BuscarPorContinente($pais->continente->nombreContinente);
foreach ($listaPaises as $value) {
    echo json_Encode($value);
}