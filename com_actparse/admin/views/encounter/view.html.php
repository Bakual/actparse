<?php
/* Thomas Hunziker - www.bakual.net - Januar 2010 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class ActparseViewEncounter extends JView
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

		JToolBarHelper::title(JText::_('COM_ACTPARSE_ENCOUNTER'), 'impressions');

		// Build the actions for new and existing records.
		if ($isNew)  {
			// For new records, check the create permission.
			JToolBarHelper::apply('encounter.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('encounter.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::custom('encounter.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);

			JToolBarHelper::cancel('encounter.cancel', 'JTOOLBAR_CANCEL');
		} else {
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
			JToolBarHelper::apply('encounter.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('encounter.save', 'JTOOLBAR_SAVE');

			JToolBarHelper::custom('encounter.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			JToolBarHelper::custom('encounter.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);

			JToolBarHelper::cancel('encounter.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}