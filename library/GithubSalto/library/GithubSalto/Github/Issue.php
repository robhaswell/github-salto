<?php

class GithubSalto_Github_Issue extends CM_Class_Abstract {

	/** @var GithubSalto_Github_Repository */
	private $_repository;

	/** @var int */
	private $_number;

	/** @var string */
	private $_title;

	/** @var string */
	private $_body;

	/** @var string */
	private $_assignee;

	/** @var int */
	private $_commentCount;

	/** @var GithubSalto_Github_Comment[]|null */
	private $_commentList;

	/** @var string */
	private $_state;

	/**
	 * @param GithubSalto_Github_Repository $repository
	 * @param int                           $number
	 * @param string                        $title
	 * @param string                        $body
	 * @param string                        $assignee
	 * @param int                           $commentCount
	 * @param string                        $state
	 */
	function __construct(GithubSalto_Github_Repository $repository, $number, $title, $body, $assignee, $commentCount, $state) {
		$this->_repository = $repository;
		$this->_number = (int) $number;
		$this->_title = (string) $title;
		$this->_body = (string) $body;
		$this->_assignee = (string) $assignee;
		$this->_commentCount = (int) $commentCount;
		$this->_state = (string) $state;
	}

	/**
	 * @return GithubSalto_Github_Repository
	 */
	public function getRepository() {
		return $this->_repository;
	}

	/**
	 * @return GithubSalto_Github_Github
	 */
	public function getGithub() {
		return $this->getRepository()->getGithub();
	}

	/**
	 * @return int
	 */
	public function getNumber() {
		return $this->_number;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->_title;
	}

	/**
	 * @return string
	 */
	public function getBody() {
		return $this->_body;
	}

	/**
	 * @return string
	 */
	public function getAssignee() {
		return $this->_assignee;
	}

	/**
	 * @return string
	 */
	public function getState() {
		return $this->_state;
	}

	/**
	 * @return GithubSalto_Github_Comment[]
	 */
	public function getCommentList() {
		if (0 === $this->_commentCount) {
			return array();
		}
		if (null === $this->_commentList) {
			$this->_commentList = array();
			$page = 1;
			while (null !== $page) {
				$content = $this->getGithub()->getClient()->api('issue')->comments()->all($this->getRepository()->getUsername(), $this->getRepository()->getName(), $this->getNumber(), $page);
				foreach ($content as $comment) {
					$this->_commentList[] = new GithubSalto_Github_Comment($this, $comment['body']);
				}
				$page = $this->getGithub()->getPageNext();
			}
		}
		return $this->_commentList;
	}

	/**
	 * @return string
	 */
	public function getFulltext() {
		$fulltext = $this->getTitle() . PHP_EOL . $this->getBody() . PHP_EOL;
		foreach ($this->getCommentList() as $comment) {
			$fulltext .= $comment->getBody() . PHP_EOL;
		}
		return $fulltext;
	}

	public function __toString() {
		return $this->getRepository()->__toString() . ' #' . $this->getNumber();
	}
}
