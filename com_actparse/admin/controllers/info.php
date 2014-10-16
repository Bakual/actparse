<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

/**
 * Controller class for the ACT Parse Component
 *
 * @since  1.0
 */
class ActparseControllerInfo extends JControllerLegacy
{
	/**
	 * Creates some needed fields in the encounter table.
	 *
	 * @return  void
	 */
	public function create_fields()
	{
		$app = JFactory::getApplication();
		$db  = JFactory::getDBO();

		$this->setRedirect('index.php?option=com_actparse&view=info');

		// Check if encounter table is present.
		$query = "SHOW TABLES LIKE 'encounter_table'";
		$db->setQuery($query);

		if (!$db->loadAssoc())
		{
			$app->enqueueMessage(JText::_('COM_ACTPARSE_TABLE_DOES_NOT_EXIST'), 'warning');

			return;
		}

		// Check if fields already exists.
		$fieldlist = array('id', 'catid', 'rid', 'checked_out', 'checked_out_time', 'published');
		$query = "SHOW COLUMNS FROM `encounter_table`";
		$db->setQuery($query);
		$columns = $db->loadAssocList();

		foreach ($columns as $column)
		{
			if (in_array($column['Field'], $fieldlist))
			{
				$app->enqueueMessage(JText::_('COM_ACTPARSE_FIELDS_EXISTS_ALREADY'), 'notice');

				return;
			}
		}

		$query = "ALTER TABLE `encounter_table` \n"
				. "CHANGE `starttime` `starttime` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', \n"
				. " ADD `id` int(11) NOT NULL AUTO_INCREMENT, \n"
				. " ADD `catid` int(11) NOT NULL DEFAULT '0', \n"
				. " ADD `rid` int(11), \n"
				. " ADD `checked_out` int(11) NOT NULL, \n"
				. " ADD `checked_out_time` datetime NOT NULL, \n"
				. " ADD `published` tinyint(1) NOT NULL DEFAULT 1, \n"
				. " ADD PRIMARY KEY (`id`)";
		$db->setQuery($query);

		if (!$db->execute())
		{
			$app->enqueueMessage(JText::_('COM_ACTPARSE_FAILED_ALTERING_TABLE'), 'warning');

			return;
		}

		$app->redirect('index.php?option=com_actparse&view=info', JText::_('COM_ACTPARSE_JOB_DONE'));
	}

	/**
	 * Updates the catid field in the encounter table
	 *
	 * @return  void
	 */
	public function update_fields()
	{
		$app = JFactory::getApplication();
		$db  = JFactory::getDBO();

		$this->setRedirect('index.php?option=com_actparse&view=info');

		// Check if encounter table is present.
		$query = "SHOW TABLES LIKE 'encounter_table'";
		$db->setQuery($query);

		if (!$db->loadAssoc())
		{
			$app->enqueueMessage(JText::_('COM_ACTPARSE_TABLE_DOES_NOT_EXIST'), 'warning');

			return;
		}

		// Check if fields already exists.
		$catid = false;
		$query = "SHOW COLUMNS FROM `encounter_table`";
		$db->setQuery($query);
		$columns = $db->loadAssocList();

		foreach ($columns as $column)
		{
			if ($column['Field'] == 'catid')
			{
				$catid = true;

				break;
			}
		}

		if (!$catid)
		{
			$app->enqueueMessage(JText::_('COM_ACTPARSE_FIELDS_DONT_EXIST'), 'warning');

			return;
		}

		$query = "ALTER TABLE `encounter_table` \n"
				. "CHANGE `catid` `catid` int(11) NOT NULL DEFAULT '0'";
		$db->setQuery($query);

		if (!$db->execute())
		{
			$app->enqueueMessage(JText::_('COM_ACTPARSE_FAILED_ALTERING_TABLE'), 'warning');

			return;
		}

		$app->redirect('index.php?option=com_actparse&view=info', JText::_('COM_ACTPARSE_JOB_DONE'));
	}
}
