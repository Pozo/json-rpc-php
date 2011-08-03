<?php
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
?>