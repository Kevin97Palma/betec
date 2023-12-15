<?php

function Generador($identificador=NULL, $tabla=NULL, $campo){
      global $pdo;
      $digitos=8;

      $sql_buscar = $pdo->query("SELECT * FROM $tabla");
      $row = $sql_buscar->fetch();


      $cant = $row[$campo];
      $str_ceros = "";
    
      $nletra = strlen($identificador);
      $ncant = strlen($cant);
    
      $ceros = $digitos - ($nletra + $ncant);
      $i = 1;
    
      while($i <= $ceros){
        $str_ceros .= "0";
        $i += 1;
      }
    
      $cant++;
      $codigo = $identificador.$str_ceros.$cant;
      return $codigo;
    }



    function Actualizador($tabla,$campo){
      global $pdo;

      $sql_buscar = $pdo->query("SELECT * FROM $tabla");
      $row = $sql_buscar->fetch();
      $cant = $row[$campo]+1;


      $sql_buscar = $pdo->query("UPDATE parametros set $campo='$cant'");
           
      return $sql_buscar;
    }



    function buscames($mimes){
      switch($mimes)
      {   
          case "January":
          $monthNameSpanish = "Enero";
          break;

          case "February":
          $monthNameSpanish = "Febrero";
          break;

          case "March":
          $monthNameSpanish = "Marzo";
          break;

          case "April":
          $monthNameSpanish = "Abril";
          break;
          case "May":
          $monthNameSpanish = "Mayo";
          break;

          case "June":
          $monthNameSpanish = "Junio";
          break;
          case "July":
          $monthNameSpanish = "Julio";
          break;
          case "August":
          $monthNameSpanish = "Agosto";
          break;
          case "September":
          $monthNameSpanish = "Septiembre";
          break;
          case "October":
          $monthNameSpanish = "Octubre";
          break;
          case "November":
          $monthNameSpanish = "Noviembre";
          break;

          case "December":
          $monthNameSpanish = "Diciembre";
          break;

      }
      return $monthNameSpanish;


  }

  
  function TildesHtml($cadena) 
  { 
      return str_replace(array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ"),
   array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;",
                                                  "&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;"), $cadena);     
  }

   
?>
