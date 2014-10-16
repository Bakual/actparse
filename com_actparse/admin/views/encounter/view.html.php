<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

class ActparseViewEncounter extends JViewLegacy
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

		return parent::display($tpl);
	}

	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$isNew = ($this->item->id == 0);

		JToolBarHelper::title(JText::_('COM_ACTPARSE_' . $isNew ? 'ADD' : 'EDIT' . 'ENCOUNTER'), 'user');

		// Build the actions for new and existing records.
		if ($isNew)
		{
			JToolBarHelper::apply('encounter.apply');
			JToolBarHelper::save('encounter.save');
			JToolbarHelper::save2new('encounter.save2new');
			JToolBarHelper::cancel('encounter.cancel');
		}
		else
		{
			JToolBarHelper::apply('encounter.apply');
			JToolBarHelper::save('encounter.save');
			JToolbarHelper::save2new('encounter.save2new');
			JToolbarHelper::save2new('encounter.save2copy');
			JToolBarHelper::cancel('encounter.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
