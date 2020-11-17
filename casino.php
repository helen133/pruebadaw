<html>
<head>
   <style>
   .titulo{
    background-color: black;
    color: white;
    text-align: center;
    width: 400px;
    display: inline-block;
    }
 
   .resultado{
      margin: 0 auto;
      width: 400px;
      text-align: center;
      border: 1px solid black;
      background-color:Paleturquoise;
   }   
  
   .casino{
      margin: 0 auto;
      background-color:#283BF7;
      width: 400px;
      text-align: center;
      border: 1px solid black;
      }
   </style>
</head>

<body>
<?php
session_start();
$visitas = 1;

//COOKIES, si existe
if(isset($_COOKIE['visitas'])){
    $visitas = $_COOKIE['visitas'];
}

if (empty($_SESSION['iniciarSesion'])){
    if(empty($_POST['cantidadInicial'])) {
?>
<div class="casino">
  <div class="titulo"><h1>BIENVENIDO AL CASINO</h1></div>
  <p>Esta es su <?=$visitas?> visita</p>
	  <form method="POST" >
	     Introduce el dinero con el que va a jugar: <input type="number" name="cantidadInicial">
	  </form>
</div>
<?php 
     exit();
}else{
    $_SESSION['iniciarSesion'] = $_POST['cantidadInicial'];
 }
}

//Al dar a la opcion apostar
if (isset ($_POST['apostar'])){ //
    $cantidadApostar = $_POST['cantidadApostar']; //OBTENGO EL DINERO INICIAL QUE TIENE EL USUARIO
    if ($cantidadApostar > $_SESSION['iniciarSesion']){
        echo "<br>No puedes apostar mas de lo que tienes";
    }else{
        $_POST['jugar']; //obtener si ha ha puesto impar o par   
        $numero = rand(1,2) == 1 ? "Impar" : "Par"; 
        
        echo "<div class=\"resultado\">Resultado de la apuesta " .$numero. "</div>";
        if ($numero == $_POST['jugar']){ 
            echo "<div class=\"resultado\"><h4> GANASTE!! Menuda suerte </div>" ;
            $_SESSION['iniciarSesion'] += $cantidadApostar;
        }else{   
            echo "<div class=\"resultado\"><h4> oh PERDISTE No te desanimes </div>";
            $_SESSION['iniciarSesion'] -= $cantidadApostar;
        }
    }
}

//si abandona o ya no tiene dinero 
if (isset ($_POST['abandonar']) || $_SESSION['iniciarSesion'] == 0){
    $visitas++;
    setcookie("visitas", $visitas, time()+ 2 * 7 * 24 * 3600); //se incrementa las cookies
   ?>
   <div class="resultado">
     <h4> Muchas gracias por jugar con nosotros </h4>
     <p>Su resultado final es de:  <?=$_SESSION['iniciarSesion'] ?> Euros</p> 
    </div>
    <?php 
    session_destroy();
    exit();
}

?>
<div class="casino">
<p> Dispone de  <?= $_SESSION['iniciarSesion']?> para jugar <p>
  <form method="POST">
  Introduzca una cantidad <input type="number" name="cantidadApostar"> <br>
  <h5>Tipo de apuesta:</h5>
    Par  <input type="radio" value="Par"   name="jugar" checked='checked'>
    Impar<input type="radio" value="Impar" name="jugar" checked='checked'><br>
 <button name="apostar"  >Apostar</button>
 <button name="abandonar">Abandonar</button>
</form>
</div>

</body>
</html>
