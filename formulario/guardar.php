<?php
// DETALLES DE CONFIGURACI�N Y CONSTANTES
define('DIR', '/home/alberto/Escritorio/SUBIDAS');
define('TAMA�OMAXTOTAL'  ,300000);
define('TAMA�OMAXFICHERO',200000);

define('ERROR_NO_JPG_PNG',     5000);
define('ERROR_MAX_TAMA�OIMG',  5001);
define('ERROR_MAX_TAMA�OTOTAL',5002);

$codigosErrorSubida= [
    UPLOAD_ERR_OK         => 'Subida correcta',
    UPLOAD_ERR_INI_SIZE   => 'El tama�o del archivo excede el admitido por el servidor',  // directiva upload_max_filesize en php.ini
    UPLOAD_ERR_FORM_SIZE  => 'El tama�o del archivo excede el admitido por el cliente',  // directiva MAX_FILE_SIZE en el formulario HTML
    UPLOAD_ERR_PARTIAL    => 'El archivo no se pudo subir completamente',
    UPLOAD_ERR_NO_FILE    => 'No se seleccion� ning�n archivo para ser subido',
    UPLOAD_ERR_NO_TMP_DIR => 'No existe un directorio temporal donde subir el archivo',
    UPLOAD_ERR_CANT_WRITE => 'No se pudo guardar el archivo en disco',  // permisos
    UPLOAD_ERR_EXTENSION  => 'Una extensi�n PHP evito la subida del archivo',  // extensi�n PHP
    // Mis errores adicionales
    ERROR_NO_JPG_PNG         => 'Formato de Imagen no admitido',
    ERROR_MAX_TAMA�OIMG      => 'El archivo supera el tama�o m�ximo permitido',
    ERROR_MAX_TAMA�OTOTAL    => 'El total de los archivos supera el m�ximo permitido '.(TAMA�OMAXTOTAL/1000).' KB'
];


?>
<html>
<head>
<meta charset="UTF-8">
<title> Guardar Imagenes</title>
</head>
<body>
<?php

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    include_once 'selectimagenes.html';
} 
// Proceso el formulario m�todo POST
else {
   
    
    if ( contarFicherosRecibidos() == 0 ) {
        avisojs(" Error No se ha indicado ning�n fichero.");
        header("Refresh:0");
        exit();
    }
    
    // Cargo la informaci�n de los ficheros recibidos
    $tablaficheros=[];
    foreach ( $_FILES as $fichero => $propiedades){
        if (isset($fichero) && $propiedades['name'] != "") {
            $resu = testImagenOK($propiedades);
            // No hay error
            if ($resu ==  UPLOAD_ERR_OK ){
                // Guardo el nombre del fichero => el tama�o y el nombr$e del fichero temporal
                $tablaficheros[$propiedades['name']] =  [ $propiedades['size'], $propiedades['tmp_name'] ];
            } else {
                $msg =" Error al subir el archivo <b>" . $propiedades['name']."</b><br>";
                $msg .= $codigosErrorSubida[$resu]."<br>";
                echo $msg;
                
            }
        }
    }
   
    
    $tama�ototal = calcularTama�oTotal($tablaficheros); 
    if ($tama�ototal > TAMA�OMAXTOTAL) {
        avisojs( $codigosErrorSubida[ERROR_MAX_TAMA�OTOTAL]);
        header("Refresh:0");
    }
    
    // Muevo los ficheros 
    foreach ($tablaficheros as $nombre => $valor){
   
        // Fichero a crear y fichero temporal [1]
        if ( moverImagenADestino($nombre,$valor[1])) {
            echo "<span style='font-size:50px;color:green;'>&#9786</span> Se ha copiado el archivo <b> " . $nombre . '</b><br />';
        } else {
            echo "<span style='font-size:50px;color:red;  '>&#9785</span> No se puede guardar el archivo <b> ".$nombre."</b><br>";
        }
    }
    
}
// --------------------------
//   FUNCIONES AUXILIARES
//  -------------------------

/**
 * 
 * @param array $imagenPropiedades
 * @return int 
 * Devuelve el UPLOAD_ERR_OK sin no ha habido errores o un codigo de error 
 * asociado a la tabla de mensajes 
 *  
 */
function testImagenOK(array $imagenPropiedades ):int
{
    // obtengo el c�digo de error que me da PHP
    $error = $imagenPropiedades['error'];
    
    // Si no ha habido error ckequeo el resto de condiciones
    if ($error == UPLOAD_ERR_OK) {
        $tipo = $imagenPropiedades['type'];
        if ($tipo != "image/jpeg" && $tipo != "image/png") {
            $error= ERROR_NO_JPG_PNG;
        } else  if ($imagenPropiedades['size'] > TAMA�OMAXFICHERO) {
            $error = ERROR_MAX_TAMA�OIMG;
        }
    }
    return $error;
}
/**
 * Mueve el fichero temporal al destino
 * @param string $nombrefichero
 * @param string $ficherotemporal
 * @return boolean True si �xito o false en caso contrario
 */
function moverImagenADestino(string $nombrefichero,string $ficherotemporal):bool{
    // No se puede mover si el fichero existe
    if ( file_exists(DIR.'/'.$nombrefichero)) {
        return false;
    }
    else {
        return  move_uploaded_file($ficherotemporal, DIR . '/' . $nombrefichero);
    }
}

/**
 * 
 * @param string $msg - Mensaje a mostrar 
 */

function avisojs (string $msg):void {
    echo "<script> alert (\"".$msg."\") </script>";
}

/**
 * 
 * @param array $tablaficheros
 * @return int - Suma del tama�o de todos los ficheros descargados
 */
function calcularTama�oTotal(array $tablaficheros):int
{
    $tama�ototal = 0;
    foreach ($tablaficheros as $valor) {
        $tama�ototal += $valor[0]; // El tama�o
    }
    return $tama�ototal;
}


/**
 *  @return int - N�mero de ficheros que se han completado en el formulario 
 */

function contarFicherosRecibidos():int
{
    //  Chequeo si no se ha enviado ningun fichero
    $contadorficheros =0;
    foreach ($_FILES as $propiedades) {
        if ( $propiedades['name'] != "")
            $contadorficheros++;
    }
    return $contadorficheros;
}

?>
</body>
</html>
