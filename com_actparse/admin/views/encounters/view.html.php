<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the actparse Component
 */
class ActparseViewEncounters extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	function display( $tpl = null )
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->zones		= $this->get('Zones');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_ACTPARSE_MENU_ENCOUNTER'), 'impressions.png');
		JToolBarHelper::publishList('encounters.publish');
		JToolBarHelper::unpublishList('encounters.unpublish');
		JToolbarHelper::divider();
		JToolbarHelper::custom('move.display', 'move.png', 'move.png', 'COM_ACTPARSE_ASSIGN_RAID');
		JToolbarHelper::custom('movecat.display', 'move.png', 'move.png', 'COM_ACTPARSE_ASSIGN_CATEGORY');
		JToolbarHelper::divider();
		JToolBarHelper::deleteList('', 'encounters.delete');
		JToolBarHelper::editList('encounter.edit');
		JToolBarHelper::addNew('encounter.add');
		JToolbarHelper::divider();
		JToolBarHelper::preferences('com_actparse',600);
	}
}
