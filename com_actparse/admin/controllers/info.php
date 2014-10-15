<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;


class ActparseControllerInfo extends JControllerLegacy
{
	function __construct()
	{
		parent::__construct();
	}

	function display()
	{
		parent::display();
	}

	function create_fields()
	{
		$app = JFactory::getApplication();

		$db			= &JFactory::getDBO();
		$fieldlist	= array('id','catid','rid','checked_out','checked_out_time','published');

		$query_check = "SHOW TABLES LIKE 'encounter_table'";
		$db->setQuery($query_check);
		$check_result = $db->loadAssoc();

		$this->setRedirect('index.php?option=com_actparse&view=info');

		if (is_null($check_result)) {
			return JError::raiseWarning( '', JText::_( 'COM_ACTPARSE_TABLE_DOES_NOT_EXIST' ) );
		}

		$query_check = "SHOW COLUMNS FROM encounter_table";
		$db->setQuery($query_check);
		$check_result = $db->loadAssocList();

		foreach ($check_result as $column) {
			if (in_array($column['Field'],$fieldlist)) {
				return JError::raiseWarning( '', JText::_( 'COM_ACTPARSE_FIELDS_EXISTS_ALREADY' ) );
			}
		}
		$query	= "ALTER TABLE `encounter_table`" .
				"\n CHANGE `starttime` `starttime` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'," .
				"\n ADD `id` int(11) NOT NULL AUTO_INCREMENT," .
				"\n ADD `catid` int(11) NOT NULL DEFAULT '0'," .
				"\n ADD `rid` int(11)," .
				"\n ADD `checked_out` int(11) NOT NULL," .
				"\n ADD `checked_out_time` datetime NOT NULL," .
				"\n ADD `published` tinyint(1) NOT NULL DEFAULT 1," .
				"\n ADD PRIMARY KEY  (`id`)";
		$db->setQuery($query);
		if(!$db->query()) {
			return JError::raiseWarning( '', JText::_( 'COM_ACTPARSE_FAILED_ALTERING_TABLE' ) );
		}
		$msg	= JText::_('COM_ACTPARSE_JOB_DONE');
		$app->redirect('index.php?option=com_actparse&view=info', $msg);
	}

	function update_fields()
	{
		$app = JFactory::getApplication();

		$db			= &JFactory::getDBO();

		$query_check = "SHOW TABLES LIKE 'encounter_table'";
		$db->setQuery($query_check);
		$check_result = $db->loadAssoc();

		$this->setRedirect('index.php?option=com_actparse&view=info');

		if (is_null($check_result)) {
			return JError::raiseWarning( '', JText::_( 'COM_ACTPARSE_TABLE_DOES_NOT_EXIST' ) );
		}

		$query_check = "SHOW COLUMNS FROM encounter_table";
		$db->setQuery($query_check);
		$check_result = $db->loadAssocList();

		foreach ($check_result as $column) {
			if ($column['Field'] == 'catid') {
				$catid = true;
				continue;
			}
		}
		if (!$catid) {
			return JError::raiseWarning( '', JText::_( 'COM_ACTPARSE_FIELDS_DONT_EXIST' ) );
		}
		$query	= "ALTER TABLE `encounter_table` \n"
				. "CHANGE `catid` `catid`  int(11) NOT NULL DEFAULT '0'";
		$db->setQuery($query);
		if(!$db->query()) {
			return JError::raiseWarning( '', JText::_( 'COM_ACTPARSE_FAILED_ALTERING_TABLE' ) );
		}
		$msg	= JText::_('COM_ACTPARSE_JOB_DONE');
		$app->redirect('index.php?option=com_actparse&view=info', $msg);
	}
}
