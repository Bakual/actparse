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
class ActparseViewRaids extends JViewLegacy
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
	 */
	public function display($tpl = null)
	{
		ActparseHelper::addSubmenu('raids');

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
		JToolBarHelper::title(JText::_('COM_ACTPARSE_RAIDS_MANAGER'), 'drawer raids');
		JToolBarHelper::addNew('raid.add');
		JToolBarHelper::editList('raid.edit');
		JToolbarHelper::divider();
		JToolBarHelper::publishList('raids.publish');
		JToolBarHelper::unpublishList('raids.unpublish');
		JToolbarHelper::divider();
		JToolBarHelper::deleteList('', 'raids.delete');
		JToolBarHelper::preferences('com_actparse');
	}
}
