<?php

// CLI execution only
if (PHP_SAPI !== 'cli') {
	die();
}

define('_JEXEC', 1);
define('JPATH_BASE', dirname(__DIR__));

if (file_exists(JPATH_BASE . '/includes/defines.php')) {
	include_once JPATH_BASE . '/includes/defines.php';
}

if (file_exists(JPATH_BASE . '/includes/framework.php')) {
	require_once JPATH_BASE . '/includes/framework.php';
}

class DeleteUsersCli extends JApplicationCli
{

	public function __construct()
	{
		parent::__construct();
		$this->config = new JConfig();

		// Register app class to JFactory::$application for JFactory::getApplication()
		JFactory::$application = $this;
	}

	public function execute()
	{
		jimport('joomla.access.access');
		jimport('joomla.user.user');

		// Get all registered users
		$users = JAccess::getUsersByGroup(2);
		foreach ($users as $user) {
			$thisUser = JFactory::getUser($user);

			// If count is > 1, the user belongs to more groups
			if (count($thisUser->groups) == 1) {
				$this->out('Deleting user ' . $thisUser->username);
				$thisUser->delete();
			}
		}
	}
}

JApplicationCli::getInstance('DeleteUsersCli')->execute();
