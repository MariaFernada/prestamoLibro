<?php
 require_once('dbconn.php');
 require_once('lib/nusoap.php'); 
 $server = new nusoap_server();
/* Fetch all book data */
function fetchBookDataAll(){
    global $dbconn;
    $sql = "select id_libro,titulo,autor,isbn,genero FROM libro";
  
      $stmt = $dbconn->prepare($sql);
      $stmt->execute();
      $data = $stmt->fetchall(PDO::FETCH_ASSOC);
      return json_encode($data); 
      $dbconn = null;
  }

  $server->configureWSDL('servicioPrestamo', 'urn:libro');
  $server->register('fetchBookDataAll',
  array('all' => 'xsd:string'),    //parameter
  array('data' => 'xsd:string'),   //output
  'urn:libro',                      //namespace
  'urn:libro#fetchBookDataAll',        //soapaction
  'rpc',                               // style
  'encoded',                           // use
  'trae la informacion de todos los libros'   // description
  );  
  $server->service(file_get_contents("php://input"));


?>