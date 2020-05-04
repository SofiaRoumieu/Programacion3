<?php
interface IBuscador
{
    function BuscarPorPais($pais);
    function BuscarPorContinente($continente);
    function BuscarPorIdioma($idioma);
    function BuscarPorCapital($capital);
}