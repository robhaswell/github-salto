<?php

class GithubSalto_Graph_Graph extends CM_Class_Abstract {

	/** @var GithubSalto_Graph_Dependency[] */
	private $_dependencyList = array();

	/** @var GithubSalto_Github_Issue[] */
	private $_issueListParsed = array();

	/** @var GithubSalto_Graph_Node_Abstract[] */
	private $_nodeList = array();

	/**
	 * @param GithubSalto_Graph_Node_Abstract $source
	 * @param GithubSalto_Graph_Node_Abstract $destination
	 */
	public function addDependency(GithubSalto_Graph_Node_Abstract $source, GithubSalto_Graph_Node_Abstract $destination) {
		$this->_dependencyList[] = new GithubSalto_Graph_Dependency($source, $destination);
	}

	/**
	 * @return GithubSalto_Graph_Dependency[]
	 */
	public function getDependencyList() {
		return $this->_dependencyList;
	}

	/**
	 * @param GithubSalto_Github_Issue $issue
	 */
	public function parseDependencies(GithubSalto_Github_Issue $issue) {
		if (isset($this->_issueListParsed[$issue->__toString()])) {
			return;
		}
		$this->_issueListParsed[$issue->__toString()] = $issue;

		$repository = $issue->getRepository();
		$github = $issue->getGithub();
		preg_match_all($this->_getDependencyRegexp(), $issue->getFulltext(), $matches, PREG_SET_ORDER);
		foreach ($matches as $match) {
			$dependencyName = (string) $match[1];
			$dependencyIssue = null;
			$nodeSource = $this->addNodeIssue($issue);
			if (preg_match('#^https://github.com/(.+?)/(.+?)/(?:pull|issues)/(\d+?)$#', $dependencyName, $matches)) {
				$dependencyIssue = $github->getRepository($matches[1], $matches[2])->getIssue($matches[3]);
			} elseif (preg_match('/^#(\d+?)$/', $dependencyName, $matches)) {
				$dependencyIssue = $repository->getIssue($matches[1]);
			}
			if ($dependencyIssue) {
				$nodeDestination = $this->addNodeIssue($dependencyIssue);
				$this->parseDependencies($dependencyIssue);
			} else {
				$nodeDestination = $this->addNodeString($dependencyName);
			}
			$this->addDependency($nodeSource, $nodeDestination);
			if ($dependencyIssue) {
				$this->parseDependencies($dependencyIssue);
			}
		}
	}

	/**
	 * @param GithubSalto_Github_Issue $issue
	 * @return GithubSalto_Graph_Node_Issue
	 */
	public function addNodeIssue(GithubSalto_Github_Issue $issue) {
		$key = (string) $issue;
		if (!isset($this->_nodeList[$key])) {
			$this->_nodeList[$key] = new GithubSalto_Graph_Node_Issue($this->_getNextNodeId(), $issue);
		}
		return $this->_nodeList[$key];
	}

	/**
	 * @param string $text
	 * @return GithubSalto_Graph_Node_String
	 */
	public function addNodeString($text) {
		$key = (string) $text;
		if (!isset($this->_nodeList[$key])) {
			$this->_nodeList[$key] = new GithubSalto_Graph_Node_String($this->_getNextNodeId(), $text);
		}
		return $this->_nodeList[$key];
	}

	/**
	 * @return GithubSalto_Graph_Node_Abstract[]
	 */
	public function getNodeList() {
		return $this->_nodeList;
	}

	/**
	 * @param string $name
	 * @return string
	 */
	public function getDot($name) {
		$name = (string) $name;
		$dot = 'digraph "' . str_replace('"', '\"', $name) . '" {' . PHP_EOL;
		foreach ($this->getNodeList() as $node) {
			$nodeAttributes = array(
				'label' => '"' . str_replace('"', '\"', $node->getLabel()) . '"',
				'color' => $node->getColor(),
				'shape' => $node->getShape(),
			);
			$nodeAttributeParts = array();
			foreach ($nodeAttributes as $key => $value) {
				$nodeAttributeParts[] = $key . '=' . $value;
			}
			$dot .= $node->getId() . ' [' . implode(', ', $nodeAttributeParts) . '];' . PHP_EOL;
		}
		foreach ($this->getDependencyList() as $dependency) {
			$dot .= $dependency->getSource()->getId() . ' -> ' . $dependency->getDestination()->getId() . ';' . PHP_EOL;
		}
		$dot .= '}' . PHP_EOL;
		return $dot;
	}

	/**
	 * @return int
	 */
	private function _getNextNodeId() {
		return count($this->_nodeList) + 1;
	}

	/**
	 * @return string
	 */
	private function _getDependencyRegexp() {
		return (string) self::_getConfig()->dependencyRegexp;
	}
}
