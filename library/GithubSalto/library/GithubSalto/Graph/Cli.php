<?php

class GithubSalto_Graph_Cli extends CM_Cli_Runnable_Abstract {

	/**
	 * @synchronized
	 */
	public function create() {
		$this->_getOutput()->writeln('okok.');
	}

	public static function getPackageName() {
		return 'graph';
	}
}
