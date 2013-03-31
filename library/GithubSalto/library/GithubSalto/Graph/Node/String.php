<?php

class GithubSalto_Graph_Node_String extends GithubSalto_Graph_Node_Abstract {

	/** @var string */
	private $_text;

	/**
	 * @param int    $id
	 * @param string $text
	 */
	public function __construct($id, $text) {
		parent::__construct($id);
		$this->_text = $text;
	}

	/**
	 * @return string
	 */
	public function getText() {
		return $this->_text;
	}

	public function getLabel() {
		return $this->getText();
	}

	public function getShape() {
		return 'plaintext';
	}
}
