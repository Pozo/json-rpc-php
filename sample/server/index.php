<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	include("../../lib/server/JsonRpcServer.php");
	include("MathServiceImpl.php");
    session_start();
	/*
	$server = new JsonRpcServer(file_get_contents("php://input"));

	$server->addService(new MathServiceImpl());
	$server->processingRequests();

	*/
	$server = new AuthenticatorJsonRpcServer(file_get_contents("php://input"));

	$server->addService(new MathServiceImpl());
    $server->addService(new PersonServiceImpl());
	$server->processingRequests();
}
?>