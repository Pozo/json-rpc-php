<?php
include("../../lib/client/JsonJsonRpcClient.php");
include("../../lib/utils/ObjectList.php");

$client = new JsonRpcClient("http://localhost/jsonrpc/sample/server/");
$listOfCalls = new ObjectList();

$listOfCalls->add(new RpcRequest("add",array(33,77)));
$listOfCalls->add(new RpcRequest("divide",array(44,11),true));
$listOfCalls->add(new RpcRequest("subtract",array(2,12.3)));

echo '<pre>';
var_dump($client->subtract(2,9.2));
echo '</pre>';

echo '<pre>';
var_dump($client->callBatch($listOfCalls));
echo '</pre>';
?>