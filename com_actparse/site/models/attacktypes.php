<?php
/* Thomas Hunziker - www.bakual.ch - Januar 2010 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class ActparseModelAttacktypes extends JModelList
{
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

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

		$query->where('at.encid = "'.$this->getState('encid').'"');
		if ($this->getState('attacker')){
			$query->where('at.attacker = "'.$this->getState('attacker').'"');
		}
		if ($this->getState('victim')){
			$query->where('at.victim = "'.$this->getState('victim').'"');
		}
		if ($this->getState('swingtype')){
			$or = $this->getState('swingtype2') ? ' OR at.swingtype = "'.$this->getState('swingtype2').'"' : '';
			$query->where('at.swingtype = "'.$this->getState('swingtype').'"'.$or);
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'type')).' '.$db->getEscaped($this->getState('list.direction', 'ASC')));

		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		// Initialise variables.
		$app	= JFactory::getApplication();
		$params	= $app->getParams();

		$limit	= (int)$params->get('limit', '');
		// List state information
		$search = JRequest::getString('filter-search', '');
		$this->setState('filter.search', $search);

		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
		$this->setState('list.limit', $limit);

		$limitstart = JRequest::getInt('limitstart', 0);
		$this->setState('list.start', $limitstart);

		$orderCol	= JRequest::getCmd('filter_order', $params->get('default_order', 'type'));
		$this->setState('list.ordering', $orderCol);

		$listOrder	=  JRequest::getCmd('filter_order_Dir', $params->get('default_order_dir', 'ASC'));
		$this->setState('list.direction', $listOrder);

		$show_npc	= JRequest::getWord('show_npc', 0);
		$this->setState('show_npc', $show_npc);

		$encid		= JRequest::getCmd('encid');
		$this->setState('encid', $encid);

		$attacker		= JRequest::getString('attacker');
		$this->setState('attacker', $attacker);

		$victim			= JRequest::getString('victim');
		$this->setState('victim', $victim);

		$swingtype		= JRequest::getString('swingtype');
		$this->setState('swingtype', $swingtype);

		$swingtype2			= JRequest::getString('swingtype2');
		$this->setState('swingtype2', $swingtype2);

		$dmgtype		= JRequest::getString('type');
		$this->setState('dmgtype', $dmgtype);

		$this->setState('filter.state',	1);

		// Load the parameters.
		$this->setState('params', $params);
	}

	function getCrumbs()
	{
		$db			=& JFactory::getDBO();

		$query	= "SELECT rt.raidname, et.rid, et.encid, et.title as encname, ct.name as combatant, dt.type as dmgtype \n"
				. "FROM encounter_table AS et \n"
				. "LEFT JOIN #__actparse_raids AS rt ON et.rid = rt.id \n"
				. "LEFT JOIN combatant_table AS ct ON et.encid = ct.encid \n"
				. "LEFT JOIN damagetype_table AS dt ON et.encid = dt.encid \n"
				. "WHERE et.encid = '".$this->getState('encid')."' \n"
				. "AND ct.name = '".$this->getState('attacker').$this->getState('victim')."' \n"
				. "AND dt.type = '".$this->getState('dmgtype')."'";

		$db->SetQuery($query);

		$crumbs	= $db->loadAssoc();	// Lädt Resultat als Array (_data['id'])

		return $crumbs;
	}
}