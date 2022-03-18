<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class ActparseModelAttacktypes extends JModelList
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
				'at.*, et.rid, ROUND(at.`encdps`) AS `encdps`, ROUND(at.`chardps`) AS `chardps`, ROUND(at.`dps`) AS `dps`'
			)
		);
		$query->from('`attacktype_table` AS at');

		// Join over the encounter table
		$query->join('RIGHT', 'encounter_table AS et ON et.encid = at.encid');

		$query->where('at.encid = ' . $db->quote($db->escape($this->getState('encid'))));

		if ($this->getState('attacker'))
		{
			$query->where('at.attacker = ' . $db->quote($db->escape($this->getState('attacker'))));
		}

		if ($this->getState('victim'))
		{
			$query->where('at.victim = ' . $db->quote($db->escape($this->getState('victim'))));
		}

		if ($this->getState('swingtype'))
		{
			$or = $this->getState('swingtype2') ? ' OR at.swingtype = ' . (int) $this->getState('swingtype2') : '';
			$query->where('at.swingtype = ' . (int) $this->getState('swingtype') . $or);
		}

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'type')).' '.$db->escape($this->getState('list.direction', 'ASC')));

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
		$search = $jinput->getString('filter-search', '');
		$this->setState('filter.search', $search);

		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'));
		$this->setState('list.limit', $limit);

		$limitstart = $jinput->getInt('limitstart', 0);
		$this->setState('list.start', $limitstart);

		$orderCol = $jinput->getCmd('filter_order', $params->get('default_order', 'type'));
		$this->setState('list.ordering', $orderCol);

		$listOrder =  $jinput->getCmd('filter_order_Dir', $params->get('default_order_dir', 'ASC'));
		$this->setState('list.direction', $listOrder);

		$show_npc = $jinput->getWord('show_npc', 0);
		$this->setState('show_npc', $show_npc);

		$encid = $jinput->getCmd('encid');
		$this->setState('encid', $encid);

		$attacker = $jinput->getString('attacker');
		$this->setState('attacker', $attacker);

		$victim = $jinput->getString('victim');
		$this->setState('victim', $victim);

		$swingtype = $jinput->getString('swingtype');
		$this->setState('swingtype', $swingtype);

		$swingtype2 = $jinput->getString('swingtype2');
		$this->setState('swingtype2', $swingtype2);

		$dmgtype = $jinput->getString('type');
		$this->setState('dmgtype', $dmgtype);

		$this->setState('filter.state', 1);

		// Load the parameters.
		$this->setState('params', $params);
	}

	function getCrumbs()
	{
		$db = Factory::getDBO();

		$query = $db->getQuery(true);
		$query->select('rt.raidname, et.rid, et.encid, et.title as encname, ct.name as combatant, dt.type as dmgtype');
		$query->from('encounter_table as et');
		$query->join('LEFT', '`#__actparse_raids` AS rt ON et.rid = rt.id');
		$query->join('LEFT', '`combatant_table` AS ct ON et.encid = ct.encid');
		$query->join('LEFT', '`damagetype_table` AS dt ON et.encid = dt.encid');
		$query->where('et.encid = ' . $db->quote($db->escape($this->getState('encid'))));
		$query->where('ct.name = ' . $db->quote($db->escape($this->getState('attacker') . $this->getState('victim'))));
		$query->where('dt.type = ' . $db->quote($db->escape($this->getState('dmgtype'))));

		$db->setQuery($query);

		return $db->loadAssoc();
	}
}
