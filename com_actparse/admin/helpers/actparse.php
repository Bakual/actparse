<?php

// No direct access
defined('_JEXEC') or die;

class ActparseHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 * @since	1.6
	 */
	public static function addSubmenu($vName = 'main')
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_ACTPARSE_MENU_ENCOUNTER'),
			'index.php?option=com_actparse&view=encounters',
			$vName == 'encounters'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_ACTPARSE_MENU_RAID'),
			'index.php?option=com_actparse&view=raids',
			$vName == 'raids'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_ACTPARSE_MENU_CATEGORY'),
			'index.php?option=com_categories&extension=com_actparse',
			$vName == 'categories'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_ACTPARSE_MENU_INFO'),
			'index.php?option=com_actparse&view=info',
			$vName == 'info'
		);
	}
}