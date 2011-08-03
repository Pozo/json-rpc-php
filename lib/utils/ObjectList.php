<?php

class ObjectList implements Iterator, Countable {
	private $_position = 0;
	private $_collectName;
	private $_count;

	public function add($object) {
		array_push($this->{$this->_collectName}, $object);
		++$this->_count;
	}
	public function get($position) {
		return $this->{$this->_collectName}[$this->_position];
	}

	public function __construct($collectName = 'collect') {
		$this->_collectName = strtolower($collectName);
		$this->{$this->_collectName} = array();

		$this->_position = 0;
	}

	function rewind() {
		$this->_position = 0;
	}

	function current() {
		return $this->{$this->_collectName}[$this->_position];
	}

	function key() {
		return $this->_position;
	}

	function next() {
		++$this->_position;
	}

	function valid() {
		return isset($this->{$this->_collectName}[$this->_position]);
	}

	public function count() {
		return $this->_count;
	}

	private function __clone() {
	}

}
?>