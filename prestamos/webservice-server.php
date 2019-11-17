<?php

 /** 
   @Descripción: Servicio web del lado del servidor de información de libros:
  Este Sctript crea un servicio web utilizando la biblioteca php NuSOAP. 
  La función fetchBookData acepta el ISBN y envía información del libro.
 */
 require_once('dbconn.php');
 require_once('lib/nusoap.php'); 
 $server = new nusoap_server();

 /* Method to isnter a new book */
function insertBook($titulo, $autor, $isbn, $genero){
  global $dbconn;
  $sql_insert = "insert into libro (titulo, autor, isbn, genero) values ( :titulo, :autor, :isbn, :genero)";
  $stmt = $dbconn->prepare($sql_insert);
  // insert a row
  $result = $stmt->execute(array(':titulo'=>$titulo, ':autor'=>$autor, ':isbn'=>$isbn, ':genero'=>$genero));
  if($result) {
    return json_encode(array('status'=> 200, 'msg'=> 'success'));
  }
  else {
    return json_encode(array('status'=> 400, 'msg'=> 'fail'));
  }
  
  $dbconn = null;
  }
/* Fetch 1 book data */
function fetchBookData($isbn){
	global $dbconn;
	$sql = "SELECT id, titulo, autor, id_libro, isbn, genero FROM libro
	        where isbn = :isbn";
  // prepare sql and bind parameters
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':isbn', $isbn);

    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    return json_encode($data);
    $dbconn = null;
}
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


$server->configureWSDL('booksWebServiceUSTA', 'urn:book');

$server->register('fetchBookData',
			array('isbn' => 'xsd:string'),  //parameter
			array('data' => 'xsd:string'),  //output
			'urn:book',                     //namespace
			'urn:book#fetchBookData',        //soapaction
      'rpc',                               // style
      'encoded',                           // use
      'trae la informacion de un libro'   // description
      ); 
$server->register('fetchBookDataAll',
      array('all' => 'xsd:string'),    //parameter
      array('data' => 'xsd:string'),   //output
      'urn:book',                      //namespace
      'urn:book#fetchBookDataAll',        //soapaction
      'rpc',                               // style
      'encoded',                           // use
      'trae la informacion de todos los libros'   // description
      );  
$server->register('insertBook',
			array('titulo' => 'xsd:string', 'autor' => 'xsd:string', 'isbn' => 'xsd:string', 'genero' => 'xsd:string'),  //parameter
			array('data' => 'xsd:string'),  //output
			'urn:book',   //namespace
			'urn:book#fetchBookData' ,        //soapaction
      'rpc',                               // style
      'encoded',                           // use
      'Inserta un libro a la base de datos'   // description
    );
$server->service(file_get_contents("php://input"));

?>