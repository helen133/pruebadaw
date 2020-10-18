<html>
 <head>
  <meta charset="UTF-8">
  <title>Juego</title>  
</head>

<body>
<h1>¡Piedra, papel, tijera!</h1>
<p> Actualize la página para mostrar otra partida. </p>


<?php
  define ('PIEDRA1',  "&#x1F91C;"); 
  define ('PIEDRA2',  "&#x1F91B;");
  define ('TIJERAS',  "&#x1F596;");
  define ('PAPEL',    "&#x1F91A;" );
  
 //constantes piedra, papel, tijeras
  define('J_PIEDRA', 1);
  define('J_PAPEL' , 2);
  define('J_TIJERA', 3); 
  
  $jugador1 = numeroAlAzar();
  $jugador2 = numeroAlAzar();
  $resultado = ganador($jugador1, $jugador2);  
  
  //generar el numero aleatorio de los jugadores
  function numeroAlAzar (){
      $num = random_int(1, 3);
      return $num;
  }
  
  //verificar quien ha ganado 
  //0 = empate
  //1 = gana jugador 1
  //2 = ganar jugador 2
  function ganador ($jugador1, $jugador2){
      $resu = -1;
      if ($jugador1 == $jugador2){
          $resu = 0;
      }else if ($jugador1 == J_PIEDRA){
         switch ($jugador2){
              case J_PAPEL : $resu = 2; break;
              case J_TIJERA: $resu = 1; break;
          }
      }else if ($jugador1 == J_PAPEL){
          switch ($jugador2){
              case J_PIEDRA: $resu = 1; break;
              case J_TIJERA: $resu = 2; break;
          }
      }else if ($jugador1 == J_TIJERA){
          switch ($jugador2){
              case J_PIEDRA: $resu = 2; break;
              case J_PAPEL : $resu = 1; break;
          }
      }
      return $resu;
  }
  
  //mostrar figura piedra, papel, tijera
  function mostrarFigura ($jugador1, $jugador2){
      if ($jugador1 == J_PIEDRA){
          echo '<span  style="font-size: 550%;">' .PIEDRA1. '</span>';
      }else if ($jugador1 == J_PAPEL){
          echo '<span  style="font-size: 550%;">' .PAPEL. '</span>';
      }else if ($jugador1 == J_TIJERA){
          echo '<span style="font-size: 550%;">' .TIJERAS. '</span>';
      }
      
      if ($jugador2 == J_PIEDRA){
          echo '<span  style="font-size: 550%;">'  .PIEDRA2. '</span>';
      }else if ($jugador2 == J_PAPEL){
          echo '<span  style="font-size: 550%;">' .PAPEL.  '</span>';
      }else if ($jugador2 == J_TIJERA){
          echo '<span  style="font-size: 550%;">' .TIJERAS. '</span>';
      }
  }
?>

 <table>
    <tr>
	    <th>Jugador 1</th>
	    <th>Jugador 2</th>
	</tr>
	
	<tr>
	    <td colspan="2"><?php mostrarFigura($jugador1, $jugador2)?></td>
	</tr>
	
	<tr>
	     <?php if ($resultado == 0){ ?> <th colspan="2" style =text-align:center;> ¡Empate!</th>
   <?php }else if ($resultado == 1){ ?> <th colspan="2" style =text-align:center;> Gana el jugador 1</th> 
   <?php }else if ($resultado == 2){ ?> <th colspan="2" style =text-align:center;> Gana el jugador 2</th> <?php } ?>
	</tr>
</table>

</body>
</html>