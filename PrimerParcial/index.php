<?php
include 'vendor/autoload.php';
include_once './datos.php';
include_once './gestionJWT.php';
require_once './response.php';

use \Firebase\JWT\JWT;

$path= $_SERVER['PATH_INFO'] ?? "";
$metodo= $_SERVER['REQUEST_METHOD'] ?? "";
$response = new Response();

switch($path)
{
    case '/usuario':
        if($metodo=='POST')
        {
            if(isset($_POST['email'])&&isset($_POST['clave']))
            {
                $cliente=new stdClass();
                $cliente->email=$_POST['email'];
                $cliente->clave=$_POST['clave'];
                $rsta=Datos::Guardar('archivos/users.json', $cliente);
                $response->data = $rsta;
                echo json_encode($response);
            }
            else{
                $response->data = "Faltan datos";
                $response->status = "fail";
                echo json_encode($response);
            }
        }
        else{
            $response->data = "Solo peticiones POST";
            $response->status = "fail";
            echo json_encode($response);
        }
    break;
    case '/login':
        if($metodo=='POST')
        {
            if(isset($_POST['email'])&&isset($_POST['clave']))
            {
                    $email = $_POST['email'];
                    $clave = $_POST['clave'];
                    $usuarioEncontrado = Datos::buscarUsuario($clave,$email);
                    if($usuarioEncontrado != null)
                    {
                        $payload = GestionJWT::CrearToken($usuarioEncontrado);;
                        if($payload != null)
                        {
                            echo json_encode($payload);
                            $response->data = $payload;
                            echo json_encode($response);
                        }
                    }
                    else
                    {
                        $response->data = "Usuario no encontrado";
                        $response->status = "fail";
                        echo json_encode($response);
                    }
            }   
            else
            {
                $response->data = "Faltan datos";
                $response->status = "fail";
                echo json_encode($response);
            }
        }
        else{
            $response->data = "Solo peticiones POST";
            $response->status = "fail";
            echo json_encode($response);
        }
    break;
    case '/materia':
        if($metodo=='POST')
        {
            $headers = getallheaders();
            if  (isset($headers['token']))
            {
                GestionJWT::ValidarToken($headers['token']);
                
                if(isset($_POST['nombre'])&&isset($_POST['cuatrimestre']))
                    {
                            $listaMaterias=Datos::Leer('archivos/materias.json');
                            $maxId=(count($listaMaterias))+1;
                            
                            $materia = new stdClass();
                            $materia->nombre=($_POST['nombre']);
                            $materia->cuatrimestre= ($_POST['cuatrimestre']);
                            $materia->id= $maxId;

                            $rsta=Datos::Guardar('archivos/materias.json',$materia);
                            $response->data = $rsta;
                            echo json_encode($response);
                            
                    }
            }
            else
            {
                $response->data = "Token invalido";
                $response->status = "fail";
                echo json_encode($response);
            }
        }
        else if($metodo=='GET')
        {
            $headers = getallheaders();
            if  (isset($headers['token']))
            {
                GestionJWT::ValidarToken($headers['token']);
                $listaMaterias=Datos::Leer('archivos/materias.json');
                $lista = array();
                foreach($listaMaterias as $materia){
                    $materiaAuxiliar= new stdClass();
                    $materiaAuxiliar->nombre=$materia->nombre;
                    $materiaAuxiliar->cuatrimestre=$materia->cuatrimestre;
                    $materiaAuxiliar->id=$materia->id;
                    array_push($lista, $materiaAuxiliar);
                }
                echo json_encode($lista);
            }
            else
            {
                $response->data = "Token invalido";
                $response->status = "fail";
                echo json_encode($response);
            }
        }
        else
        {
            $response->data = "Peticion invalida";
            $response->status = "fail";
            echo json_encode($response);   
        }
    break;
    case '/profesor':
        if($metodo=='POST')
        {
            $headers = getallheaders();
            if  (isset($headers['token'])){
                GestionJWT::ValidarToken($headers['token']);
            
                if(isset($_POST['nombre'])&&isset($_POST['legajo'])&&isset($_FILES['foto']))
                {
                    if(Datos::buscarProfesor($_POST['legajo']))
                    {
                        $response->data = "El numero de legajo ya existe, ingrese uno nuevo";
                        $response->status = "fail";
                        echo json_encode($response);   
                    }
                    else
                    {
                        $profesor = new stdClass();
                        $profesor->nombre=($_POST['nombre']);
                        $profesor->legajo= ($_POST['legajo']);
                        
                        $rsta=Datos::Guardar('archivos/profesores.json',$profesor);
                        Datos::GuardarImagen($_POST['legajo']);
                        $response->data = $rsta;
                        echo json_encode($response);
                    }
                }
            
            
            }
        }
        else if($metodo=='GET')
        {
            $headers = getallheaders();
            if  (isset($headers['token']))
            {
                GestionJWT::ValidarToken($headers['token']);
                $listaProfesores=Datos::Leer('archivos/profesores.json');
                $lista = array();
                foreach($listaProfesores as $profesor){
                    $profesorAuxiliar= new stdClass();
                    $profesorAuxiliar->nombre=$profesor->nombre;
                    $profesorAuxiliar->legajo=$profesor->legajo;
                    array_push($lista, $profesorAuxiliar);
                }
                echo json_encode($lista);
            }
            else
            {
                $response->data = "Token invalido";
                $response->status = "fail";
                echo json_encode($response);
            }
        }
        else
        {
            $response->data = "Peticion invalida";
            $response->status = "fail";
            echo json_encode($response);   
        }
    break;
    case '/asignacion':
        if($metodo=='POST')
        {
            $headers = getallheaders();
            if  (isset($headers['token'])){
                GestionJWT::ValidarToken($headers['token']);
            
                if(isset($_POST['legajo'])&&isset($_POST['materia'])&&isset($_POST['turno']))
                {
                    if($_POST['turno']!='noche' && $_POST['turno']!='mañana')
                    {
                        $response->data = "El turno es invalido, ingrese Noche o Mañana";
                        $response->status = "fail";
                        echo json_encode($response);   
                    }
                    else
                    {
                        $asignacion = new stdClass();
                        $asignacion->materia=($_POST['materia']);
                        $asignacion->legajo= ($_POST['legajo']);
                        $asignacion->turno= ($_POST['turno']);
                        if(Datos::ValidarAsignacion($asignacion))
                        {
                            $rsta=Datos::Guardar('archivos/materias-profesores.json',$asignacion);
                            Datos::GuardarImagen($_POST['legajo']);
                            $response->data = $rsta;
                            echo json_encode($response);
                        }
                        else
                        {
                            $response->data = "Ya existe una asignacion con estas caracteristicas";
                            $response->status = "fail";
                            echo json_encode($response); 
                        }
                    }
                }
            
            
            }
        }
        else if($metodo=='GET')
        {
            $headers = getallheaders();
            if  (isset($headers['token']))
            {
                GestionJWT::ValidarToken($headers['token']);
                $listaAsignaciones=Datos::Leer('archivos/materias-profesores.json');
                $lista = array();
                foreach($listaAsignaciones as $asignacion){
                    $asignacionAuxiliar= new stdClass();
                    $asignacionAuxiliar->legajo=$asignacion->legajo;
                    $asignacionAuxiliar->materia=$asignacion->materia;
                    $asignacionAuxiliar->turno=$asignacion->turno;
                    array_push($lista, $asignacionAuxiliar);
                }
                echo json_encode($lista);
            }
            else
            {
                $response->data = "Token invalido";
                $response->status = "fail";
                echo json_encode($response);
            }
        }
        else
        {
            $response->data = "Peticion invalida";
            $response->status = "fail";
            echo json_encode($response);   
        }
    break;
    
}