<?php

define ('DESTINO' , 'C:\Users\aaa\Desktop\imgusers');
define ('TAMAÑO_INDIVIDUAL', 100000);
define ('TAMAÑO_TOTAL' , 300000);
define ('NUM_FICHEROS', 2);

$errorSubirFichero = [
    0 => "No hay errores",
    1 => "Supera el tamaño maximo indicado por el servidor",
    2 => "Supera el tamaño maximo indicado por el cliente",
    3 => "El archivo se subio parcialmente",
    4 => "No se ha subido ningun archivo",
    6 => "Falta la carpeta temporal",
    7 => "No se puede escribir en el directorio especificado",
    8 => "Una extension de PHP ha detenido la subida"
];

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    include_once 'formulario.html';  
}else{
    //SI NO EXISTE NINGUN FICHERO
    if (isset($_FILES['fichero']['name'][0])){

        //Solo se pueden subir dos ficheros, de ser mas no lo permite 
        if (contarFicheros() > NUM_FICHEROS){
            echo "<br>ERROR! No se puede subir mas de 2 archivos";
            exit();
        }
        
        //recorremos todos los archivos que se han subido
        $totalCapacidadFichero = 0;
        for ($i=0; $i<contarFicheros(); $i++){
            $errorFichero = $_FILES['fichero']['error'][$i];
            $capacidad_Fichero = $_FILES['fichero'] ['size'][$i];
            $totalCapacidadFichero += $capacidad_Fichero;
            
            //VERIFICA SI EXISTE ALGUN ERROR DE PHP 
            if ($errorFichero > 0){
                $msg = "";
                $msg = "<br>ERROR! $errorFichero: " .$errorSubirFichero[$errorFichero];
                echo $msg;
                exit();
            }
            
           //verificamos el formato de imagen
            if ($_FILES['fichero']['type'][$i] =="image/jpeg" || $_FILES['fichero']['type'][$i] == "image/png" || $_FILES['fichero']['type'][$i] =="image/jpg"){
                $temporalFichero = $_FILES['fichero']['tmp_name'][$i];
                $nombreFichero   = $_FILES['fichero']['name'][$i];
                $tamañoFichero   = $_FILES['fichero']['size'][0];   //ver el tamaño individual 
           
                //tamaño de un fichero
                if (contarFicheros() == 1) {
                    if ($tamañoFichero > TAMAÑO_INDIVIDUAL){
                        echo "<br>ERROR! Sobrepasa el tamaño permitido para un fichero";
                        exit();
                    }
                }
                
                //tamaño total de ficheros 
                if ($totalCapacidadFichero > TAMAÑO_TOTAL){
                    echo "<br>ERROR! Sobrepasa el total permitido de capacidad de todos los ficheros";
                    exit();
                }
                
                if (file_exists(DESTINO .'/'.$nombreFichero)){
                    echo "<br>Ese fichero ya existe";
                }else{
                    //mover los ficheros
                    if(move_uploaded_file($temporalFichero, DESTINO .'/'. $nombreFichero)){
                        echo "<br>Se ha subido correctamente";
                    }else{
                        echo "<br>ERROR! No se ha subido correctamente";
                    }
                }
               
            }else{
                echo "<br>ERROR! No es formato permitido";
            }
        }
    }else{ 
        echo "<br>ERROR! hay ningun archivo para subir"; 
    } 
}
    
//*************************************
// FUNCIONES NECESARIAS
//*************************************

function contarFicheros():int{
    return $num = count($_FILES['fichero']['name']); 
}
?>