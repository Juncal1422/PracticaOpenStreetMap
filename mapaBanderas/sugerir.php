<?php
require 'config.php';
$pdo = Basedatos::getConexion();

if (!empty($_GET['concello']))
{

	$codigos=$pdo->prepare("select distinct codigoconcello from praiasbazuis where concello like :busqueda");
	$codigos->bindValue(':busqueda','%'.$_GET['concello'].'%');
	$codigos->execute();

	while ($filaCodConcello = $codigos->fetch()){

		$datosConcello=$pdo->prepare("select * from casadoconcello where codigopostal like :busqueda");
		$datosConcello->bindValue(':busqueda',$filaCodConcello['codigoconcello']);
		$datosConcello->execute();


		while ($filaDatosConcello = $datosConcello->fetch()){
			echo "<div id='".$filaDatosConcello['codigopostal']."' class='concello'>" . $filaDatosConcello['concello'] . "</div> 
			<script>
			$('#".$filaDatosConcello['codigopostal']."').click(function(evt){
				$('#sugerencias').hide();
				$('#inputConcello').val('".$filaDatosConcello['concello']."');
				centrarMapa('".$filaDatosConcello['codigopostal']."');
				fijarPlayas('".$filaDatosConcello['codigopostal']."');
				});
				</script>";
			}
		}
	}
	?>