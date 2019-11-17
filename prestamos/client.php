<?php
require_once('lib/nusoap.php');
$error  = '';
$result = array();
$result_all = array();
$response = '';
$wsdl = "http://localhost:8081/prestamoLibro/prestamos/server.php?wsdl";


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Book Store Web Service</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<h2>Información del libro</h2>
  <table class="table">
    <thead>
      <tr>
	  <th>id_libros</th>
	  <th>Titulo</th>
	  <th>Autor</th>
	  <th>ISBN</th>
		<th>Genero</th>
  
   
        
      </tr>
    </thead>
    <tbody>
    <tr>
		        <td><?php echo '$result->id_libro'; ?></td>
		        <td><?php echo '$result->titulo'; ?></td>
		        <td><?php echo '$result->autor;' ?></td>
		        <td><?php echo '$result->isbn;'?></td>	
		        <td><?php echo '$result->genero'; ?></td>
		      </tr>
              </tbody>
  </table>


</body>
</html>