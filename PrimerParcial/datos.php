<?php
class Datos
{
    public static function Guardar($archivo,$objeto)
    {
        $arrayJSON = Datos::Leer($archivo);
        array_push($arrayJSON, $objeto);
        $file = fopen($archivo, 'w');
        $rta = fwrite($file, json_encode($arrayJSON));
        fclose($file);

        return $rta;    
    }

    static public function Leer($archivo) {
        $file = fopen($archivo, 'r');
        $arrayString = fread($file, filesize($archivo));
        $arrayJSON = json_decode($arrayString);
        fclose($file);

        return $arrayJSON;
    }

    static public function buscarUsuario($clave, $email) 
    {
        $lista = Datos::Leer('archivos/users.json');

        $usuarioEncontrado = '';
        foreach ($lista as $value) {
            if($value->clave==$clave)
            {
                $usuarioEncontrado = $value;
                break;
            }
        }
        return $usuarioEncontrado;
    }

    static public function buscarProfesor($legajo) 
    {
        $lista = Datos::Leer('archivos/profesores.json');

        $profesorEncontrado = false;
        foreach ($lista as $value) {
            if($value->legajo==$legajo)
            {
                $profesorEncontrado = true;
                break;
            }
        }
        return $profesorEncontrado;
    }

    public static function GuardarImagen($legajo){
        $tmp_name = $_FILES['foto']['tmp_name'];
        $name= $_FILES['foto']['name'];
        $nombre=$legajo.'-'.$name;
        $carpeta = 'imagenes/';
        echo $carpeta;
        echo move_uploaded_file($tmp_name, $carpeta . $nombre);
    }

    public static function ValidarAsignacion($asignacion)
    {
        $lista = Datos::Leer('archivos/materias-profesores.json');

        $puedeAsignar = true;
        foreach ($lista as $value) {
            if(($value->legajo==$asignacion->legajo)&&($value->materia==$asignacion->materia)
            &&($value->turno==$asignacion->turno))
            {
                $puedeAsignar = false;
                break;
            }
        }
        return  $puedeAsignar;
    }
}