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
class ActparseViewMove extends JViewLegacy
{
	protected $items;
	protected $raids;

	function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->raids		= $this->get('Raids');

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
		JToolbarHelper::save('move.move', 'Move');
		JToolBarHelper::cancel('move.cancel', 'Close');
	}
}
