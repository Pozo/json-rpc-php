[﻿JSON-RPC 2.0](http://groups.google.com/group/json-rpc/web/json-rpc-2-0) Client/Server library for PHP
###Requirements
PHP 5.2.6+

###Used predefined classes and interfaces
 - [ReflectionClass](http://www.php.net/manual/en/class.reflectionclass.php) @ `lib/server/Service`
 - [ReflectionMethod](http://www.php.net/manual/en/class.reflectionmethod.php) @ `lib/server/Service`
 - [Exception](http://www.php.net/manual/en/class.exception.php) @ `lib/server/JsonRpcExceptions`
 - [Countable](http://www.php.net/manual/en/class.countable.php) @ `lib/utils/ObjectList` 
 - [Iterator](http://www.php.net/manual/en/class.iterator.php) @ `lib/utils/ObjectList`

#How do I use the client?
After you instantiate the `JsonRpcClient` with the server URL, there have two option to send a request. Both method using the `RpcRequest` class @ `lib/client/` which help us to sending a well formatted request. Step by step tuturial soon.
##Single request
###Sending a request
So, triggering a single request after `$client = new JsonRpcClient('http://serverurl');` you can use one of the following methods:


####__call() magic method(recommended)
Parameters sequence must represent the same as server implementation and notification not allowed

    $response = $client->add($a,$b);

Is equal with

    $response = $client->{'add'}($a,$b);

####RpcRequest
1) Sorted parameters

RIGHT

    $response = $client->call(new RpcRequest('sanitize',array(array(1,2,3))));
    $response = $client->call(new RpcRequest('deleteById',array(3)));

WRONG

    $response = $client->call(new RpcRequest('sanitize',array(1,2,3)));
    $response = $client->call(new RpcRequest('deleteById',3));

2) Named parameters
Sequence is not necessary, the server will sorting params if these exits **case sensitive**

    $response = $client->call(new RpcRequest('add',array('bValue'=>$b,'aValue'=>$a)));

3) Notification

    $response = $client->call(new RpcRequest('deleteAndUpdtae',array(2),true));
    $response = $client->call(new RpcRequest('update',null,true));


###Working with the response
_Please note that the client side deliberately less implemented, so you can freely manage the response, for example, wrapping the reponse object in `JsonRpcClient` and when you got an unexpected answer, throw an exception etc._

If the request is not a notification, and the process was successful, the `$response` will be an object which contain `id`,`jsonrpc` and `result` fields, otherwise the result object will contain `error` instead of `result`. The `error` object has three members, `message`,`code` and `data` which is optional. So in `$response = $client->add($a,$b);` case `var_dump($response)` will look like this if `$a` 10 `$b` 20 :

    object(stdClass)#19 (3) {
      ["jsonrpc"]=>
      string(3) "2.0"
      ["result"]=>
      int(30)
      ["id"]=>
      int(1)
    }

And if we call accidentally `addd` instead of `add` :

    object(stdClass)#19 (3) {
      ["jsonrpc"]=>
      string(3) "2.0"
      ["error"]=>
      object(stdClass)#20 (2) {
        ["code"]=>
        int(-32601)
        ["message"]=>
        string(16) "Method not found"
      }
      ["id"]=>
      int(1)
    }

##Batch request
###Sending a request
You can send more than one request at the same time. In this case you must use `ObjectList` util to collect RpcRequest objects and passing into `callBatch` method

1) No one

    $client = new JsonRpcClient("http://localhost/jsonrpc/sample/server/");
    $listOfCalls = new ObjectList();

    $listOfCalls->add(new RpcRequest("add",array(33,77)));
    $listOfCalls->add(new RpcRequest("add",array(44,11)));
    $listOfCalls->add(new RpcRequest("add",array(2,12.3)));

    $responseArray = $client->callBatch($listOfCalls);

2) One of them is a notification

    $client = new JsonRpcClient("http://localhost/jsonrpc/sample/server/");
    $listOfCalls = new ObjectList();

    $listOfCalls->add(new RpcRequest("add",array(33,77)));
    $listOfCalls->add(new RpcRequest("add",array(44,11),true));
    $listOfCalls->add(new RpcRequest("add",array(2,12.3)));

    $responseArray = $client->callBatch($listOfCalls);

3) All request is notification

    $client = new JsonRpcClient("http://localhost/jsonrpc/sample/server/");
    $listOfCalls = new ObjectList();

    $listOfCalls->add(new RpcRequest("add",array(33,77),true));
    $listOfCalls->add(new RpcRequest("add",array(44,11),true));
    $listOfCalls->add(new RpcRequest("add",array(2,12.3),true));

    $responseArray = $client->callBatch($listOfCalls);

###Accepting the response
Only difference between the Single request that the callBatch will return an array of response objects so `$responseArray` look like this in case of 1)

    array(3) {
      [0]=>
      object(stdClass)#8 (3) {
        ["jsonrpc"]=>
        string(3) "2.0"
        ["result"]=>
        int(110)
        ["id"]=>
        int(2)
      }
      [1]=>
      object(stdClass)#12 (3) {
        ["jsonrpc"]=>
        string(3) "2.0"
        ["result"]=>
        int(55)
        ["id"]=>
        int(3)
      }
      [2]=>
      object(stdClass)#13 (3) {
        ["jsonrpc"]=>
        string(3) "2.0"
        ["result"]=>
        float(14.3)
        ["id"]=>
        int(4)
      }
    }

case of 2)

    array(2) {
      [0]=>
      object(stdClass)#8 (3) {
        ["jsonrpc"]=>
        string(3) "2.0"
        ["result"]=>
        int(110)
        ["id"]=>
        int(2)
      }
      [1]=>
      object(stdClass)#12 (3) {
        ["jsonrpc"]=>
        string(3) "2.0"
        ["result"]=>
        float(14.3)
        ["id"]=>
        int(3)
      }
    }

and finally case of 3) the `$responseArray` will contain nothing so `NULL`

#How do I use the server?
You can reach the full sample application source @ `sample` directory. _Step by step tuturial soon._
##Implementing service
First of all, your service implementation must inherited from `Service` **This is necessary for all service**, because the `JsonRpcServer` will work with `Service` methods to detect callable methods and their parameters with [PHP reflection](http://php.net/manual/en/book.reflection.php) 
    The client only can reach that method which has a block comment exactly with this content :

    /* @JsonRpcMethod*/

The first space after `/*` is very important, because the reflection can not parse the comment winthout it. But, the below sample correct yet, because `Service` using [`strstr`](http://jp.php.net/manual/en/function.strstr.php) detecting the `@JsonRpcMethod` annotation.

    /**
    * @JsonRpcMethod which is ...
    */

For example, if you have some public method in `MathServiceImpl`, which not have any block comment with `@JsonRpcMethod` annotation, the client can not reach it.

**MathServiceImpl.php**

    class MathServiceImpl extends JsonRpcService {
	/** @JsonRpcMethod*/
	public function add($aValue,$bValue) {
		return $aValue+$bValue;
	}
	/** @JsonRpcMethod*/
	public function divide($aValue,$bValue) {
		return $aValue/$bValue;
	}
	/** @JsonRpcMethod*/
	public function subtract($aValue,$bValue) {
		return $aValue-$bValue;
	}
	public function notCallableByRpcAlthoughItsPublic($name) {
		return $name;
        }
    }
