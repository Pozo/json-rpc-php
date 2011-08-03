<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	include("../../lib/server/JsonRpcServer.php");
	include("../../lib/utils/ObjectList.php");
	include("MathServiceImpl.php");
	
	$server = new JsonRpcServer(file_get_contents("php://input"));

	$server->addService(new MathServiceImpl());
	$server->processingRequests();
}
?>