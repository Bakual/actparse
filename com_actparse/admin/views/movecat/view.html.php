<?php
/* Thomas Hunziker - www.bakual.ch - Januar 2010 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the actparse Component
 */
class ActparseViewMovecat extends JView
{
	protected $items;

	function display($tpl = null)
	{
		$this->items		= $this->get('Items');

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
		JToolBarHelper::title(JText::_('COM_ACTPARSE_MOVE_ENCOUNTER'), 'impressions.png');
		JToolbarHelper::save('movecat.move', 'Move');
		JToolBarHelper::cancel('movecat.cancel', 'Close');
	}
}