<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zpolgar
 * Date: 2011.10.27.
 * Time: 13:04
 * To change this template use File | Settings | File Templates.
 */
 
class PersonServiceImpl extends JsonRpcService {
	/** @JsonRpcMethod*/
	public function getPersons() {
		return array('John','Bob');
	}
	/** @JsonRpcMethod ( request_login = true ) */
	public function addPerson($personName) {
		return $personName.' added';
	}
	public function notCallableByRpc($name) {
		return $name;
	}
}
