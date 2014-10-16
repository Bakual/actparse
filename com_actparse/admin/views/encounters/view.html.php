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
class ActparseViewEncounters extends JViewLegacy
{
	/**
	 * Array of objects.
	 *
	 * @var array
	 */
	protected $items;

	/**
	 * The pagination object.
	 *
	 * @var JPagination
	 */
	protected $pagination;

	/**
	 * The state object.
	 *
	 * @var object
	 */
	protected $state;

	/**
	 * The zones.
	 *
	 * @var array
	 */
	protected $zones;

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
		ActparseHelper::addSubmenu('encounters');

		// Sanity check
		if (!$this->get('SanityEncountersTable'))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_ACTPARSE_TABLE_DOES_NOT_EXIST'), 'error');

			return;
		}

		if (!$this->get('SanityEncountersFields'))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_ACTPARSE_FAILED_ALTERING_TABLE'), 'errror');

			return;
		}

		$this->state         = $this->get('State');
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->zones         = $this->get('Zones');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_ACTPARSE_MENU_ENCOUNTER'), 'users encounters');
		JToolBarHelper::addNew('encounter.add');
		JToolBarHelper::editList('encounter.edit');
		JToolbarHelper::divider();
		JToolBarHelper::publishList('encounters.publish');
		JToolBarHelper::unpublishList('encounters.unpublish');
		JToolbarHelper::divider();
		JToolbarHelper::custom('move.display', 'archive', 'archive', 'COM_ACTPARSE_ASSIGN_RAID');
		JToolbarHelper::custom('movecat.display', 'archive', 'archive', 'COM_ACTPARSE_ASSIGN_CATEGORY');
		JToolbarHelper::divider();
		JToolBarHelper::deleteList('', 'encounters.delete');
		JToolbarHelper::divider();
		JToolBarHelper::preferences('com_actparse');
	}
}
