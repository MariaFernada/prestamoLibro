<?php

	 /*
	  ini_set('display_errors', true);
	  error_reporting(E_ALL); 
	*/
  
	require_once('lib/nusoap.php');
	$error  = '';
	$result = array();
	$result_all = array();
	$response = '';
	$wsdl = "http://localhost:8081/prestamoLibro/prestamos/webservice-server.php?wsdl";
	if(isset($_POST['sub'])){

		$isbn = trim($_POST['isbn']);
		//echo 'aca toy ISBN->'.$isbn;exit();
		if(!$isbn){
			$error = 'ISBN no puede estar en blanco.';
		}

		if(!$error){
			//create client object
			$client = new nusoap_client($wsdl, true);
			$err = $client->getError();

			if ($err) {
				echo '<h2>Error en el Constructor</h2>' . $err;
				// At this point, you know the call that follows will fail
			    exit();
			}
			 try {

				$result = $client->call('fetchBookData', array($isbn));

				$result = json_decode($result);
			  }catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
			 }
		}
	}

	if(isset($_POST['sub_all'])){

		if(!$error){
			//create client object
			$client = new nusoap_client($wsdl, true);
			$err = $client->getError();

			if ($err) {
				echo '<h2>Error en el Constructor</h2>' . $err;
				// At this point, you know the call that follows will fail
			    exit();
			}
			 try {

				$result_all = $client->call('fetchBookDataAll');
				//echo 'aca voy2= <hr>';print_r($result_all);exit();
				$result_all = json_decode($result_all);
			  }catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
			 }
		}
	}	

	/* Add new book **/
	if(isset($_POST['addbtn'])){ 
		$titulo = trim($_POST['titulo']);
		$isbn = trim($_POST['isbn']);
		$autor = trim($_POST['autor']);
		$genero = trim($_POST['genero']);

		//Perform all required validations here
		if(!$isbn || !$titulo|| !$autor|| !$genero){
			$error = 'All fields are required.';
		}

		if(!$error){
			//create client object
			$client = new nusoap_client($wsdl, true);
			$err = $client->getError();
			if ($err) {
				echo '<h2>Constructor error</h2>' . $err;
				// At this point, you know the call that follows will fail
			    exit();
			}
			 try {
				/** Call insert book method */
				 $response =  $client->call('insertBook', array($titulo, $autor, $isbn, $genero));
				 $response = json_decode($response);
			  }catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
			 }
		}
	}

	if(isset($_POST['delete'])){ 

		$id_libro = trim($_POST['id_libro']);
		//echo 'aca toy ISBN->'.$isbn;exit();
		if(!$id_libro){
			$error = 'ISBN no puede estar en blanco.';
		}

		if(!$error){
			//create client object
			$client = new nusoap_client($wsdl, true);
			$err = $client->getError();

			if ($err) {
				echo '<h2>Error en el Constructor</h2>' . $err;
				// At this point, you know the call that follows will fail
			    exit();
			}
			 try {


				$result1 = $client->call('delete', array($id_libro));

				$result1 = json_decode($result1);
			  }catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
			 }
		}
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Book Store Web Service</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
		integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
		integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
</head>
<body>
<header>
	
		<div class="row">
			<img src="imagen.png" class="img-fluid " alt="Responsive image"></img>
		</div>
	</header>
<div class="container">
<h3>Listar Libros</h3>
  <img src ='https://www.pinclipart.com/picdir/big/9-90729_ac-dc-cliparts-cono-de-libros-png-transparent.png' width=50 height=50>
  
  <hr>
  <div class='row'>
  <form class="form-inline" method = 'post' name='form1'>
        <button type="submit" name='sub_all' class="btn btn-secondary m-1">Información de todos los libros</button>

    </form>
     <br /> 
   </div>     
  <div class='row'>
  	<form class="form-inline" method = 'post' name='form1'>
  		<?php if($error) { ?> 
	    	<div class="alert alert-danger fade in">
    			<a href="#" class="close" data-dismiss="alert">&times;</a>
    			<strong>Error!</strong>&nbsp;<?php echo $error; ?> 
	        </div>
		<?php } ?>
	    <div class="form-group">
	      <label for="email m-1">ISBN:</label>
	      <input type="text" class="form-control m-1" name="isbn" id="isbn" placeholder="Ingrese el ISBN del libro" required>
	    </div>
	    <button type="submit" name='sub' class="btn btn-secondary m-1">Obtener información del libro</button>
    </form>

   </div>
   <hr>
   <h2>Información del libro</h2>
  <table class="table">
    <thead>
      <tr>
	  <th>id_libros</th>
	  <th>Titulo</th>
	  <th>Autor</th>
	  <th>ISBN</th>
		<th>Genero</th>
		<th></th>
  
   
        
      </tr>
    </thead>
    <tbody>
    <?php  //select id_libro,titulo,autor,isbn,genero FROM libro
    	if($result){
			if($result){?>
		      <tr>
		        <td><?php echo $result->id_libro; ?></td>
		        <td><?php echo $result->titulo; ?></td>
		        <td><?php echo $result->autor; ?></td>
		        <td><?php echo $result->isbn; ?></td>	
				<td><?php echo $result->genero; ?></td>
				<td></td>
		      </tr>
	  
	  <?php }
  		else{ ?>
  			<tr>
		        <td colspan='5'>Ingrese un ISBN valido y de click en el boton de traer información del libro</td>
		      </tr>
  		<?php } }else{
  			if($result_all){
  		    	foreach ($result_all as $fila => $data) {
					$id=$data->id_libro;
					
  		    		echo '<tr><td>'.$id.'</td>'.
  		    			     '<td>'.$data->titulo.'</td>'.
  		    			     '<td>'.$data->autor.'</td>'.
  		    			     '<td>'.$data->isbn.'</td>'.
							   '<td>'.$data->genero.'</td>'.
							   '<td> 
							   <form class="form-inline" method = "post" name="form2">
							   <input type="hidden" class="form-control m-1" value="'.$id.'" name="id_libro" id="id_libro" >

							   <button type="submit" name="delete" class="btn btn-danger m-1"><i class="far fa-trash-alt"></i></button>
								</form>
							   </td>
							   </tr>';
  		    	}
  		    }}	


		  ?>
		  
    </tbody>
  </table>
	<div class='row'>
	<h2>Añadir un nuevo libro</h2>
	 <?php if(isset($response->status)) {

	  if($response->status == 200){ ?>
		<div class="alert alert-success fade in">
    			<a href="#" class="close" data-dismiss="alert">&times;</a>
    			<strong>Success!</strong>&nbsp; libro añadido satisfatoriamente. 
	        </div>
	  <?php }elseif(isset($response) && $response->status != 200) { ?>
			<div class="alert alert-danger fade in">
    			<a href="#" class="close" data-dismiss="alert">&times;</a>
    			<strong>Error!</strong>&nbsp; No fue posible agregar el libro(puede intentar nuevamente o contactar al creador del servicio)
	        </div>
	 <?php } 
	 }
	 ?>
  	<form class="form-inline" method = 'post' name='form1'>
  		<?php if($error) { ?> 
	    	<div class="alert alert-danger fade in">
    			<a href="#" class="close" data-dismiss="alert">&times;</a>
    			<strong>Error!</strong>&nbsp;<?php echo $error; ?> 
	        </div>
		<?php } ?>
	    <div class="form-group">
	      <label for="email"></label>
	      <input type="text" class="form-control pm-1" name="titulo" id="titulo" placeholder="Enter titulo" required>
				<input type="text" class="form-control m-1" name="autor" id="autor" placeholder="Enter autor" required>
				<input type="text" class="form-control m-1" name="isbn" id="isbn" placeholder="Enter ISBN" required>
				<input type="text" class="form-control m-1" name="genero" id="genero" placeholder="Enter genero" required>
	    </div>
	    <button type="submit" name='addbtn' class="btn btn-secondary m-1">Añadir nuevo libro</button>
    </form>
   </div>
</div>
<br>
</body>
</html>


