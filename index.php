<?php
error_reporting(E_ALL); // Reporta todos los errores de PHP
ini_set('display_errors', 1); // Muestra los errores en pantalla
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once 'flight/Flight.php';
// require 'flight/autoload.php';

Flight::register('db', PDO::class, ['mysql:host=localhost;dbname=api', 'root', '']);


Flight::route('POST /empleado/auth',function(){
    $usuario=(Flight::request()->data->usuario);
    $correo=(Flight::request()->data->correo);

    $sql="SELECT permiso FROM empleado WHERE usuario=? and correo=?";
    $sentencia= Flight::db()->prepare($sql);
    $sentencia->bindParam(1,$usuario);
    $sentencia->bindParam(2,$correo);
    $sentencia->execute();
    $datos=$sentencia->fetchAll();
    if($datos[0][0] == 1){
        Flight::jsonp(["Permiso concedido"]);
    }
    else{
    Flight::jsonp(["Permiso denegado"]);
    };
    
});

Flight::route('GET /empleado', function () {
    $sentencia = Flight::db()->prepare("SELECT * FROM `empleado`");
    $sentencia->execute();
    $datos=$sentencia->fetchAll();
    Flight::json($datos);
});

Flight::route('POST /empleado', function () {
    $nombres=(Flight::request()->data->nombres);
    $apellidos=(Flight::request()->data->apellidos);
    $usuario=(Flight::request()->data->usuario);
    $correo=(Flight::request()->data->correo);
    $permiso=(Flight::request()->data->permiso);

    $sql="INSERT INTO empleado (nombres,apellidos,usuario,correo,permiso) VALUES(?,?,?,?,?)";
    $sentencia= Flight::db()->prepare($sql);
    $sentencia->bindParam(1,$nombres);
    $sentencia->bindParam(2,$apellidos);
    $sentencia->bindParam(3,$usuario);
    $sentencia->bindParam(4,$correo);
    $sentencia->bindParam(5,$permiso);
    $sentencia->execute();

    Flight::jsonp(["Empleado agregado"]);
});

Flight::route('DELETE /empleado', function () {
    $id=(Flight::request()->data->id);

    $sql="DELETE FROM empleado where id=?";
    $sentencia= Flight::db()->prepare($sql);
    $sentencia->bindParam(1,$id);
    $sentencia->execute();

    Flight::jsonp(["Empleado borrado"]);

});

Flight::route('PUT /empleado', function () {
    $id=(Flight::request()->data->id);
    $nombres=(Flight::request()->data->nombres);
    $apellidos=(Flight::request()->data->apellidos);
    $usuario=(Flight::request()->data->usuario);
    $correo=(Flight::request()->data->correo);
    $permiso=(Flight::request()->data->permiso);

    $sql="UPDATE empleado SET nombres=? , apellidos=?, usuario=?, correo=?, permiso=? WHERE id=?";
    $sentencia= Flight::db()->prepare($sql);
    
    $sentencia->bindParam(1,$nombres);
    $sentencia->bindParam(2,$apellidos);
    $sentencia->bindParam(3,$usuario);
    $sentencia->bindParam(4,$correo);
    $sentencia->bindParam(5,$permiso);
    $sentencia->bindParam(6,$id);
    $sentencia->execute();

    Flight::jsonp(["Empleado modificado"]);
});

Flight::route('GET /empleado/@id', function ($id) {
    $sentencia = Flight::db()->prepare("SELECT * FROM `empleado` WHERE id=?");
    $sentencia->bindParam(1,$id);
    $sentencia->execute();
    $datos=$sentencia->fetchAll();
    Flight::json($datos);
});

Flight::start();
