<?php
/**
 * @package         ACTParse
 * @subpackage      Component.Administrator
 * @author          Thomas Hunziker <admin@bakual.net>
 * @copyright   © 2022 - Thomas Hunziker
 * @license         http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;

class ActparseModelRaid extends AdminModel
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
		return Table::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_actparse.raid', 'raid', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = Factory::getApplication()->getUserState('com_actparse.edit.raid.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}
}
