<?php

class GithubSalto_Graph_Node_Issue extends GithubSalto_Graph_Node_Abstract {

	/** @var GithubSalto_Github_Issue */
	private $_issue;

	/**
	 * @param int                      $id
	 * @param GithubSalto_Github_Issue $issue
	 */
	public function __construct($id, GithubSalto_Github_Issue $issue) {
		parent::__construct($id);
		$this->_issue = $issue;
	}

	/**
	 * @return GithubSalto_Github_Issue
	 */
	public function getIssue() {
		return $this->_issue;
	}

	public function getLabel() {
		$issue = $this->getIssue();
		$repository = $issue->getRepository();
		return $repository->__toString() . ': #' . $issue->getNumber() . ' (' . $issue->getState() . ')' . PHP_EOL . $issue->getTitle();
	}

	public function getColor() {
		if ('open' === $this->getIssue()->getState()) {
			return 'green';
		}
		if ('closed' === $this->getIssue()->getState()) {
			return 'red';
		}
		return parent::getColor();
	}
}
