<?php

class GithubSalto_Github_Github extends CM_Class_Abstract {

	/** @var Github\Client */
	private $_client;

	/** @var GithubSalto_Github_Repository[] */
	private $_repositoryList = array();

	function __construct() {
		$httpClient = new Github\HttpClient\CachedHttpClient(array('cache_dir' => DIR_TMP . 'github-api-cache'));
		$this->_client = new Github\Client($httpClient);
		$this->_client->authenticate('njam', '', Github\Client::AUTH_HTTP_PASSWORD);
	}

	/**
	 * @return \Github\Client
	 */
	public function getClient() {
		return $this->_client;
	}

	/**
	 * @return int|null
	 */
	public function getPageNext() {
		$pagination = $this->_client->getHttpClient()->getLastResponse()->getPagination();
		if (!isset($pagination['next'])) {
			return null;
		}
		$query = parse_url($pagination['next'], PHP_URL_QUERY);
		parse_str($query, $queryArray);
		return (int) $queryArray['page'];
	}

	/**
	 * @param string $username
	 * @param string $name
	 * @return GithubSalto_Github_Repository
	 */
	public function getRepository($username, $name) {
		$key = $username . '/' . $name;
		if (!isset($this->_repositoryList[$key])) {
			$this->_repositoryList[$key] = new GithubSalto_Github_Repository($this, $username, $name);
		}
		return $this->_repositoryList[$key];
	}

	/**
	 * @return GithubSalto_Github_Repository[]
	 */
	public function getRepositoryList() {
		return $this->_repositoryList;
	}
}
