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
 * HTML View class for the Actparse Component
 *
 * @since  1.0
 */
class ActparseViewinfo extends JViewLegacy
{
	/**
	 * The HTML code for the sidebar.
	 *
	 * @var string
	 */
	protected $sidebar;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 *
	 * @see     JViewLegacy::loadTemplate()
	 * @since   1.0
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		ActparseHelper::addSubmenu('info');
		JToolBarHelper::title(JText::_('COM_ACTPARSE_INFO'), 'info');
		JToolBarHelper::preferences('com_actparse');
		$this->sidebar = JHtmlSidebar::render();

		parent::display($tpl);
	}

}
