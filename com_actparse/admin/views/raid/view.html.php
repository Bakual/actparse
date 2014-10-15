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

class ActparseViewRaid extends JViewLegacy
{
	protected $item;
	protected $form;

	function display($tpl = null)
	{
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);

		$isNew		= ($this->item->id == 0);

		JToolBarHelper::title(JText::_('COM_ACTPARSE_RAID'), 'impressions');

		// Build the actions for new and existing records.
		if ($isNew)  {
			// For new records, check the create permission.
			JToolBarHelper::apply('raid.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('raid.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::custom('raid.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);

			JToolBarHelper::cancel('raid.cancel', 'JTOOLBAR_CANCEL');
		} else {
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
			JToolBarHelper::apply('raid.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('raid.save', 'JTOOLBAR_SAVE');

			JToolBarHelper::custom('raid.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			JToolBarHelper::custom('raid.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);

			JToolBarHelper::cancel('raid.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
