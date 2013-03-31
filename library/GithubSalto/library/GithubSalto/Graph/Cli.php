<?php

class GithubSalto_Graph_Cli extends CM_Cli_Runnable_Abstract {

	/**
	 * @synchronized
	 */
	public function create() {
		$github = new GithubSalto_Github_Github();
		$repository = $github->getRepository('cargomedia', 'fuboo');
		$graph = new GithubSalto_Graph_Graph();

		$issueCounter = 0;
		$issues = $repository->getIssueList();
		foreach ($issues as $issue) {
			$this->_getOutput()->write('Fetching issue ' . (++$issueCounter) . '/' . count($issues) . "..\r");
			$graph->parseDependencies($issue);
		}

		foreach ($graph->getDependencyList() as $dependency) {
			//			echo $dependency . PHP_EOL;
		}

		$this->_getOutput()->write($graph->getDot($repository->getLabel()));
	}

	public static function getPackageName() {
		return 'graph';
	}
}
