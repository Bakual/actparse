<?php
/* Thomas Hunziker - www.bakual.net - Januar 2010 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modeladmin');

/**
 *ACT Parser Component Encounter Model
 *
 */
class ActparseModelEncounter extends JModelAdmin
{
	protected $text_prefix = 'COM_ACTPARSE';

	protected function canDelete($record)
	{
		return true;
	}

	protected function canEditState($record)
	{
		return parent::canEditState($record);
	}

	public function getTable($type = 'Encounter', $prefix = 'ActparseTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_actparse.encounter', 'encounter', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_actparse.edit.encounter.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);

		return $item;
	}
}