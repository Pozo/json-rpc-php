<?php
// Invalid JSON was received by the server.
// An error occurred on the server while parsing the JSON text.
class JsonRpcParseErrorException extends Exception {
	public function __construct() {
		parent::__construct("Parse error",-32700);
	}
}
// The JSON sent is not a valid Request object.
class JsonRpcInvalidRequestException extends Exception {
	public function __construct() {
		parent::__construct("Invalid Request",-32600);
	}
}
// The method does not exist / is not available.
class JsonRpcMethodNotFoundException extends Exception {
	public function __construct() {
		parent::__construct("Method not found",-32601);
	}
}
// Invalid method parameter(s).
class JsonRpcInvalidParamsException extends Exception {
	public function __construct() {
		parent::__construct("Invalid params",-32602);
	}
}
// Internal JSON-RPC error.
class JsonRpcInternalErrorException extends Exception {
	public function __construct() {
		parent::__construct("Internal error",-32603);
	}
}
// Reserved for implementation-defined server-errors.
class JsonRpcServererrorException extends Exception {
	public function __construct() {
		parent::__construct("Server error",-32099||-32000);
	}
}
?>