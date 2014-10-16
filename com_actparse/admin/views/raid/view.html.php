<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

class ActparseViewRaid extends JViewLegacy
{
	protected $item;
	protected $form;

	function display($tpl = null)
	{
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

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
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$isNew = ($this->item->id == 0);

		JToolBarHelper::title(JText::_('COM_ACTPARSE_' . $isNew ? 'ADD' : 'EDIT' . 'RAID'), 'drawer');

		// Build the actions for new and existing records.
		if ($isNew)
		{
			JToolBarHelper::apply('raid.apply');
			JToolBarHelper::save('raid.save');
			JToolbarHelper::save2new('raid.save2new');
			JToolBarHelper::cancel('raid.cancel');
		}
		else
		{
			JToolBarHelper::apply('raid.apply');
			JToolBarHelper::save('raid.save');
			JToolbarHelper::save2new('raid.save2new');
			JToolbarHelper::save2new('raid.save2copy');
			JToolBarHelper::cancel('raid.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
