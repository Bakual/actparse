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
class ActparseModelMovecat extends JModelLegacy
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
		$query->select('et.*, cat.title AS category');
		$query->from('`encounter_table` AS et');
		$query->join('LEFT', '`#__categories` AS cat ON cat.id = et.catid');
		$query->where('et.id IN (' . $cids . ')');

		return $this->_getList($query);
	}
}
