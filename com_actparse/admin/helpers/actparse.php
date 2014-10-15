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
 * Sermonspeaker Component Sermonspeaker Helper
 *
 * @since  1.0
 */
class ActparseHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @since  1.0
	 *
	 * @return void
	 */
	public static function addSubmenu($vName = 'encounters')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_ACTPARSE_MENU_ENCOUNTER'),
			'index.php?option=com_actparse&view=encounters',
			$vName == 'encounters'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ACTPARSE_MENU_RAID'),
			'index.php?option=com_actparse&view=raids',
			$vName == 'raids'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ACTPARSE_MENU_CATEGORY'),
			'index.php?option=com_categories&extension=com_actparse',
			$vName == 'categories'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ACTPARSE_MENU_INFO'),
			'index.php?option=com_actparse&view=info',
			$vName == 'info'
		);
	}
}
