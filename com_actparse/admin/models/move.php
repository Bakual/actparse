<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

/**
 *ACT Parser Component Encounter Model
 *
 */
class ActparseModelMove extends JModelLegacy
{
	/**
	 * Get Items
	 *
	 * @return  array
	 *
	 * @throws  Exception
	 */
	public function getItems()
	{
		$db   = JFactory::getDBO();
		$app  = JFactory::getApplication();
		$cid  = $app->input->getVar('cid', array(0), '', 'array');
		JArrayHelper::toInteger($cid);
		$cids = implode(',', $cid);

		$query = $db->getQuery(true);
		$query->select('et.*, rt.raidname, rt.date');
		$query->from('`encounter_table` AS et');
		$query->join('LEFT', '`#__actparse_raids` AS rt ON rt.id = et.rid');
		$query->where('et.id IN (' . $cids . ')');

		return $this->_getList($query);
	}

	/**
	 * Gets Raids
	 *
	 * @return  array
	 */
	public function getRaids()
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('raidname, date, id as value');
		$query->from('`#__actparse_raids`');
		$query->order('raidname ASC');

		$rows = $this->_getList($query);

		foreach ($rows as $row)
		{
			$row->text = $row->raidname . ' (' . JHtml::date($row->date, JText::_('DATE_FORMAT_LC4'), 'UTC') . ')';
		}

        return $rows;
	}
}
