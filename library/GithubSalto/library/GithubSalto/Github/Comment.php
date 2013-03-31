<?php

class GithubSalto_Github_Comment extends CM_Class_Abstract {

	/** @var GithubSalto_Github_Issue */
	private $_issue;

	/** @var string */
	private $_body;

	/**
	 * @param GithubSalto_Github_Issue $issue
	 * @param string                  $body
	 */
	function __construct(GithubSalto_Github_Issue $issue, $body) {
		$this->_issue = $issue;
		$this->_body = (string) $body;
	}

	/**
	 * @return GithubSalto_Github_Issue
	 */
	public function getIssue() {
		return $this->_issue;
	}

	/**
	 * @return GithubSalto_Github_Github
	 */
	public function getGithub() {
		return $this->getIssue()->getRepository()->getGithub();
	}

	/**
	 * @return string
	 */
	public function getBody() {
		return $this->_body;
	}

}
