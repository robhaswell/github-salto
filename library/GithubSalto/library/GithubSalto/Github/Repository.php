<?php

class GithubSalto_Github_Repository extends CM_Class_Abstract {

	/** @var GithubSalto_Github_Github */
	private $_github;

	/** @var string */
	private $_username;

	/** @var string */
	private $_name;

	/** @var GithubSalto_Github_Issue[]|null */
	private $_issueList;

	/**
	 * @param GithubSalto_Github_Github $github
	 * @param string                    $username
	 * @param string                    $name
	 */
	public function __construct(GithubSalto_Github_Github $github, $username, $name) {
		$this->_github = $github;
		$this->_username = (string) $username;
		$this->_name = (string) $name;
	}

	/**
	 * @return GithubSalto_Github_Github
	 */
	public function getGithub() {
		return $this->_github;
	}

	/**
	 * @return string
	 */
	public function getUsername() {
		return $this->_username;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->getUsername() . '/' . $this->getName();
	}

	/**
	 * @return GithubSalto_Github_Issue[]
	 */
	public function getIssueList() {
		if (null === $this->_issueList) {
			$this->_issueList = array();
			$page = 1;
			while (null !== $page) {
				$params = array('state' => 'open', 'page' => $page);
				$content = $this->getGithub()->getClient()->api('issue')->all($this->getUsername(), $this->getName(), $params);
				foreach ($content as $issue) {
					$this->_registerIssue($issue);
				}
				$page = $this->getGithub()->getPageNext();
			}
		}
		return $this->_issueList;
	}

	/**
	 * @param int $number
	 * @return GithubSalto_Github_Issue
	 * @throws CM_Exception
	 */
	public function getIssue($number) {
		$number = (int) $number;
		$issueList = $this->getIssueList();
		if (!array_key_exists($number, $issueList)) {
			$issue = $this->getGithub()->getClient()->api('issue')->show($this->getUsername(), $this->getName(), $number);
			$issueList = $this->_registerIssue($issue);
		}
		return $issueList[$number];
	}

	public function __toString() {
		return $this->getLabel();
	}

	/**
	 * @param array $data
	 * @return GithubSalto_Github_Issue[]
	 */
	private function _registerIssue($data) {
		$number = (int) $data['number'];
		$state = (string) $data['state'];
		$title = (string) $data['title'];
		$body = (string) $data['body'];
		$commentCount = (int) $data['comments'];
		$assignee = empty($data['assignee']) ? null : (string) $data['assignee']['login'];
		$this->_issueList[$number] = new GithubSalto_Github_Issue($this, $number, $title, $body, $assignee, $commentCount, $state);
		return $this->_issueList;
	}
}
