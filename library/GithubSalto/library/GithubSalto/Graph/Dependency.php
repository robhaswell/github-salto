<?php

class GithubSalto_Graph_Dependency extends CM_Class_Abstract {

	/** @var GithubSalto_Graph_Node_Abstract */
	private $_source;

	/** @var GithubSalto_Graph_Node_Abstract */
	private $_destination;

	/**
	 * @param GithubSalto_Graph_Node_Abstract $source
	 * @param GithubSalto_Graph_Node_Abstract $destination
	 */
	public function __construct(GithubSalto_Graph_Node_Abstract $source, GithubSalto_Graph_Node_Abstract $destination) {
		$this->_source = $source;
		$this->_destination = $destination;
	}

	/**
	 * @return GithubSalto_Graph_Node_Abstract
	 */
	public function getSource() {
		return $this->_source;
	}

	/**
	 * @return GithubSalto_Graph_Node_Abstract
	 */
	public function getDestination() {
		return $this->_destination;
	}

	public function __toString() {
		return $this->getSource() . ' -> ' . $this->getDestination();
	}
}
