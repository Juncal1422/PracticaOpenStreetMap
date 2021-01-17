<?php
require 'config.php';
$pdo = Basedatos::getConexion();

if (!empty($_GET['codigoconcello']))
{
	$arrplayas=[];
	$playas=$pdo->prepare("select distinct * from praiasbazuis where codigoconcello like :busqueda");
	$playas->bindValue(':busqueda','%'.$_GET['codigoconcello'].'%');
	$playas->execute();

	while ($fila = $playas->fetch()){
		array_push($arrplayas, $fila);
	}

	$concello=$pdo->prepare("select distinct * from casadoconcello where codigopostal like :cp");
	$concello->bindValue(':cp',$arrplayas[0]['codigoconcello']);
	$concello->execute();

	while ($fila = $concello->fetch ()){
		$objconcello=$fila;
	}
	$datos=[
		"datos" => [
			"playas" => $arrplayas,
			"concello" => $objconcello
		]
	];
	echo json_encode($datos);
}
?>