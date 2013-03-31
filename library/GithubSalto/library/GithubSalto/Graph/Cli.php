<?php

class GithubSalto_Graph_Cli extends CM_Cli_Runnable_Abstract {

	/**
	 * @param string $userName
	 * @param string $repositoryName
	 */
	public function create($userName, $repositoryName) {
		$userName = (string) $userName;
		$repositoryName = (string) $repositoryName;
		$oauthToken = $this->_getGithubOauthToken();
		$github = new GithubSalto_Github_Github($oauthToken);
		$repository = $github->getRepository($userName, $repositoryName);
		$graph = new GithubSalto_Graph_Graph();

		$issueCounter = 0;
		$issues = $repository->getIssueList();
		foreach ($issues as $issue) {
			//			$this->_getOutput()->write('Fetching issue ' . (++$issueCounter) . '/' . count($issues) . "..\r");
			$graph->parseDependencies($issue);
		}

		$this->_getOutput()->write($graph->getDot($repository->getLabel()));
	}

	/**
	 * @return string
	 */
	private function _getGithubOauthToken() {
		$tokenPath = DIR_DATA . 'github-oauth-token';
		if (CM_File::exists($tokenPath)) {
			$file = new CM_File($tokenPath);
		} else {
			$username = $this->_getInput()->read('Github username:');
			$password = $this->_getInput()->read('Github password:');
			$token = GithubSalto_Github_Github::getOauthToken($username, $password, array('repo'), 'github-salto');
			$file = CM_File::create($tokenPath, $token);
		}
		return $file->read();
	}

	public static function getPackageName() {
		return 'graph';
	}
}
