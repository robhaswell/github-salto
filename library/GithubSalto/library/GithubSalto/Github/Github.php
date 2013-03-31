<?php

class GithubSalto_Github_Github extends CM_Class_Abstract {

	/** @var Github\Client */
	private $_client;

	/** @var GithubSalto_Github_Repository[] */
	private $_repositoryList = array();

	/**
	 * @param string|null $oauthToken
	 */
	function __construct($oauthToken = null) {
		$httpClient = new Github\HttpClient\CachedHttpClient(array('cache_dir' => DIR_TMP . 'github-api-cache'));
		$this->_client = new Github\Client($httpClient);
		if (null !== $oauthToken) {
			$this->_client->authenticate($oauthToken, null, Github\Client::AUTH_URL_TOKEN);
		}
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

	/**
	 * @param string        $username
	 * @param string        $password
	 * @param string[]|null $scopes
	 * @param string|null   $note
	 * @param string|null   $noteUrl
	 * @return string
	 */
	public static function getOauthToken($username, $password, array $scopes = null, $note = null, $noteUrl = null) {
		$client = new Github\Client();
		$client->authenticate($username, $password, Github\Client::AUTH_HTTP_PASSWORD);
		$params = array();
		if (null !== $scopes) {
			$params['scopes'] = $scopes;
		}
		if (null !== $note) {
			$params['note'] = (string) $note;
		}
		if (null !== $noteUrl) {
			$params['note_url'] = (string) $noteUrl;
		}
		$response = $client->getHttpClient()->post('authorizations', array('note' => $note, 'scopes' => $scopes));
		$content = $response->getContent();
		return (string) $content['token'];
	}
}
