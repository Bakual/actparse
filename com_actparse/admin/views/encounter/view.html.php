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
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

class ActparseViewEncounter extends HtmlView
{
	protected $item;
	protected $form;

	public function display($tpl = null)
	{
		$this->item = $this->get('Item');
		$this->form = $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->addToolbar();

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', true);

		$isNew = ($this->item->id == 0);

		ToolBarHelper::title(JText::_('COM_ACTPARSE_' . $isNew ? 'ADD' : 'EDIT' . 'ENCOUNTER'), 'user');

		// Build the actions for new and existing records.
		if ($isNew)
		{
			ToolBarHelper::apply('encounter.apply');
			ToolBarHelper::save('encounter.save');
			ToolBarHelper::save2new('encounter.save2new');
			ToolBarHelper::cancel('encounter.cancel');
		}
		else
		{
			ToolBarHelper::apply('encounter.apply');
			ToolBarHelper::save('encounter.save');
			ToolBarHelper::save2new('encounter.save2new');
			ToolBarHelper::save2new('encounter.save2copy');
			ToolBarHelper::cancel('encounter.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
