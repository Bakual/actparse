<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   © 2022 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * HTML View class for the Actparse Component
 *
 * @since  1.0
 */
class ActparseViewRaids extends HtmlView
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
	 * @var Pagination
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
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 *
	 * @see     HtmlView::loadTemplate()
	 * @since   1.0
	 */
	public function display($tpl = null)
	{
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

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		ToolBarHelper::title(Text::_('COM_ACTPARSE_RAIDS_MANAGER'), 'drawer raids');
		ToolBarHelper::addNew('raid.add');
		ToolBarHelper::editList('raid.edit');
		ToolbarHelper::divider();
		ToolBarHelper::publishList('raids.publish');
		ToolBarHelper::unpublishList('raids.unpublish');
		ToolbarHelper::divider();
		ToolBarHelper::deleteList('', 'raids.delete');
		ToolBarHelper::preferences('com_actparse');
	}
}
