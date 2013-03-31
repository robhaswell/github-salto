<?php

abstract class GithubSalto_Graph_Node_Abstract extends CM_Class_Abstract {

	/** @var int */
	private $_id;

	/**
	 * @param int $id
	 */
	public function __construct($id) {
		$this->_id = (int) $id;
	}

	/**
	 * @return string
	 */
	abstract public function getLabel();

	/**
	 * @return int
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return string
	 */
	public function getColor() {
		return 'white';
	}

	/**
	 * @return string
	 */
	public function getShape() {
		return 'box';
	}

	function __toString() {
		return $this->getLabel();
	}
}
