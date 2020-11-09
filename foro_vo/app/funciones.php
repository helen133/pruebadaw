<?php
function usuarioOk($usuario, $contraseña) :bool {
 //para contar cuantas letras son  
   if (strlen($usuario) < 8){
       return false;
   }
   //para invertir el usuario 
   if ($contraseña == strrev($usuario)){
       return $usuario;
   }
}
  
   
   function letraMasRepetida($comentario){
       $mayor = 0;
       $letra = "";
       foreach (count_chars($comentario, 1) as $i => $valor){
           
           if(chr($i) != " "){
               if ($valor > $mayor){       
                   $mayor = $valor;
                   $letra = chr($i);
               }
           }
           
       }
       return $letra;
   }
   
   
//palabra mas repetida   
   function palabraMasRepetida($comentario) :string{
       $mayor = 0;
       
       $array = explode (" ", $comentario);
       $x = array_count_values($array);
       
       foreach ($x as $key => $value){
           if ($value > $mayor){
               $mayor = $value;
               $palabra = $key;
           }
       }
       return $palabra;
       
   }
 

