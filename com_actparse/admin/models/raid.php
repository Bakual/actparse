<?php
/**
 * @package     ACT Parse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class ActparseModelRaid extends JModelAdmin
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

	public function getTable($type = 'Raid', $prefix = 'ActparseTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_actparse.raid', 'raid', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_actparse.edit.raid.data', array());

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