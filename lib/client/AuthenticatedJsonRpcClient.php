<?php
 
class AuthenticatedJsonRpcClient extends JsonRpcClient {
	private $_username;
	private $_password;
	
	public function __construct($rpcUrl, $username, $password) {
		parent::__construct($rpcUrl);
		$this->_username = $username;
		$this->_password = $password;
        $this->authenticate();
	}
	public function authenticate() {
		$responseObject = $this->call(new RpcRequest('authenticate',array($this->_username,$this->_password)));
		return $responseObject;
	}
}
?>
