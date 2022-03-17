<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * HTML View class for the Actparse Component
 *
 * @since  1.0
 */
class ActparseViewEncounters extends HtmlView
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
	 * @see     JViewLegacy::loadTemplate()
	 * @since   1.0
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		// Sanity check
		if (!$this->get('SanityEncountersTable'))
		{
			Factory::getApplication()->enqueueMessage(JText::_('COM_ACTPARSE_TABLE_DOES_NOT_EXIST'), 'error');

			return;
		}

		if (!$this->get('SanityEncountersFields'))
		{
			Factory::getApplication()->enqueueMessage(JText::_('COM_ACTPARSE_FAILED_ALTERING_TABLE'), 'errror');

			return;
		}

		$this->state         = $this->get('State');
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->zones         = $this->get('Zones');
		$this->raids         = $this->get('Raids');
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
		ToolbarHelper::title(JText::_('COM_ACTPARSE_MENU_ENCOUNTER'), 'users encounters');
		ToolbarHelper::addNew('encounter.add');
		ToolbarHelper::editList('encounter.edit');
		ToolbarHelper::divider();
		ToolbarHelper::publishList('encounters.publish');
		ToolbarHelper::unpublishList('encounters.unpublish');
		ToolbarHelper::divider();
		ToolbarHelper::deleteList('', 'encounters.delete');
		ToolbarHelper::divider();

		// Batch Button
		JHtml::_('bootstrap.modal', 'collapseModal');
		$bar    = Toolbar::getInstance('toolbar');
		$layout = new FileLayout('joomla.toolbar.batch');
		$dhtml  = $layout->render(array('title' => JText::_('JTOOLBAR_BATCH')));
		$bar->appendButton('Custom', $dhtml, 'batch');

		ToolbarHelper::preferences('com_actparse');
	}
}
