<?php

class RpcError {
	private $_code;
	private $_message;
	private $_data;
	
	private $errorObject;

	public function __construct($errorMessage, $errorCode, $errorData = null) {
		$this->_code = $errorCode;
		$this->_message = $errorMessage;
		$this->_data = $errorData;
	}
	public function getErrorObject() {
		$this->buildErrorObject();
		return $this->errorObject;
	}
	public function buildErrorObject() {
		$this->errorObject = new stdClass();
		$this->setErrorCode();
		$this->setErrorMessage();
		$this->setErrorData();
	}
	public function setErrorCode() {
		$this->errorObject->code = $this->_code;
	}
	public function setErrorMessage() {
		$this->errorObject->message = $this->_message;
	}
	public function setErrorData() {
		if(!is_null($this->_data)) {
			$this->errorObject->data = $this->_data;
		}
	}
}
?>