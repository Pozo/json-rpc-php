<?php
include("../../lib/client/JsonRpcClient.php");
include("../../lib/client/AuthenticatedJsonRpcClient.php");

$listOfCalls = array();

array_push($listOfCalls,new RpcRequest("add",array(33,77)));
array_push($listOfCalls,new RpcRequest("divide",array(44,11),true));
array_push($listOfCalls,new RpcRequest("subtract",array(2,12.3)));
array_push($listOfCalls,new RpcRequest("invalidateSession"));
array_push($listOfCalls,new RpcRequest("something"));

$client = new AuthenticatedJsonRpcClient('http://localhost/jsonrpc/sample/server/','test','1234');
//$client = new JsonRpcClient('http://localhost/jsonrpc/sample/server/');
/*
echo '<pre>';
var_dump($client->subtract(2,9.2));
echo '</pre>';

echo '<pre>';
var_dump($client->callBatch($listOfCalls));
echo '</pre>';
*/
/*
 */
echo '<pre>';
var_dump($client->subtract(2,9.2));
var_dump($client->something());
var_dump($client->addPerson('asd'));
var_dump($client->call(new RpcRequest("invalidateSession",null)));
var_dump($client->something());
echo '</pre>';

?>