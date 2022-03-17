<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;


/**
 * HTML View class for the actparse Component
 */
class ActparseViewMove extends HtmlView
{
	protected $items;
	protected $raids;

	function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->raids		= $this->get('Raids');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			Factory::getApplication()->enqueueMessage(implode("\n", $errors), 'error');

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
		ToolBarHelper::title(JText::_('COM_ACTPARSE_MOVE_ENCOUNTER'), 'impressions.png');
		ToolBarHelper::save('move.move', 'Move');
		ToolBarHelper::cancel('move.cancel', 'Close');
	}
}
