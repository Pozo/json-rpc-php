<?php

class RpcResponse {
	const VERSION = "2.0";
	private $_resultBody;
	private $_id;
	
	private $responseObject;

	public function __construct($resultBody, $requestId = null) {
		$this->_resultBody = $resultBody;
		$this->_id = $requestId;
	}
	public function getRpcResponseObject() {
		$this->buildResponseObject();
		return $this->responseObject;
	}
	private function buildResponseObject() {
		$this->responseObject = new stdClass();
		$this->setResponseVersion();
		$this->setResponseBody();
		$this->setResponseId();
	}
	private function setResponseVersion() {
		$this->responseObject->jsonrpc = RpcResponse::VERSION;
	}
	private function setResponseBody() {
		if($this->_resultBody instanceof RpcError) {
			$this->responseObject->error = $this->_resultBody->getErrorObject();
		} else {
			$this->responseObject->result = $this->_resultBody;
		}
	}
	private function setResponseId() {
			$this->responseObject->id = $this->_id;
	}
	public function setResponseObjectId($id) {
			$this->_id = $id;
	}
}
?>