<?php
/* Thomas Hunziker - www.bakual.net - Januar 2010 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the actparse Component
 */
class ActparseViewRaids extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	function display( $tpl = null )
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$app = JFactory::getApplication();

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
		JToolBarHelper::title(JText::_('COM_ACTPARSE_RAIDS_MANAGER'), 'impressions.png');
		JToolBarHelper::publishList('raids.publish');
		JToolBarHelper::unpublishList('raids.unpublish');
		JToolbarHelper::divider();
		JToolBarHelper::deleteList('', 'raids.delete');
		JToolBarHelper::editList('raid.edit');
		JToolBarHelper::addNew('raid.add');
		JToolbarHelper::divider();
		JToolBarHelper::preferences('com_actparse',600);
	}
}
