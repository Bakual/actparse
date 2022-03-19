<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   © 2022 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class ActparseModelCombatants extends JModelList
{
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'ct.*'
			)
		);
		$query->from('`combatant_table` AS ct');
		$query->where('ct.encid = "' . $this->getState('encid') . '"');

		// Filter by PC/NPC
		if ($show_npc = $this->getState('show_npc'))
		{
			$query->where('ct.ally = "' . $show_npc . '"');
		}

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'encdps')) . ' ' . $db->escape($this->getState('list.direction', 'DESC')));

		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app    = Factory::getApplication();
		$params = $app->getParams();
		$jinput = $app->input;

		// List state information
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'));
		$this->setState('list.limit', $limit);

		$limitstart = $jinput->getInt('limitstart', 0);
		$this->setState('list.start', $limitstart);

		$orderCol = $jinput->getCmd('filter_order', $params->get('default_order', 'encdps'));
		$this->setState('list.ordering', $orderCol);

		$listOrder =  $jinput->getCmd('filter_order_Dir', $params->get('default_order_dir', 'DESC'));
		$this->setState('list.direction', $listOrder);

		$show_npc = $jinput->getWord('show_npc', 0);
		$this->setState('show_npc', $show_npc);

		$encid = $jinput->getCmd('encid');
		$this->setState('encid', $encid);

		$this->setState('filter.state',	1);

		// Load the parameters.
		$this->setState('params', $params);
	}

	function getCrumbs()
	{
		$db = Factory::getDBO();

		$query = $db->getQuery(true);
		$query->select('rt.raidname, et.rid, et.title as encname');
		$query->from('encounter_table as et');
		$query->join('LEFT', '#__actparse_raids AS rt ON et.rid = rt.id');
		$query->where('et.encid = ' . $db->quote($db->escape($this->getState('encid'))));

		$db->setQuery($query);

		return $db->loadAssoc();
	}
}
