<?php
/**
 * @package     ACT Parse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class ActparseModelDamagetypes extends JModelList
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
				'dt.*, ROUND(`encdps`) AS `encdps`, ROUND(`chardps`) AS `chardps`, ROUND(`dps`) AS `dps`'
			)
		);
		$query->from('`damagetype_table` AS dt');
		$query->where('dt.encid = "'.$this->getState('encid').'"');
		$query->where('dt.combatant = "'.$this->getState('combatant').'"');

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

		$combatant		= JRequest::getString('combatant');
		$this->setState('combatant', $combatant);

		$this->setState('filter.state',	1);

		// Load the parameters.
		$this->setState('params', $params);
	}

	function getCrumbs()
	{
		$db			= JFactory::getDBO();
		$combatant	= JRequest::getString('combatant');

		$query	= "SELECT rt.raidname, et.rid, et.encid, et.title as encname, ct.name as combatant \n"
				. "FROM encounter_table AS et \n"
				. "LEFT JOIN #__actparse_raids AS rt ON et.rid = rt.id \n"
				. "LEFT JOIN combatant_table AS ct ON et.encid = ct.encid \n"
				. "WHERE et.encid = '".$this->getState('encid')."' AND ct.name = '".$this->getState('combatant')."'";

		$db->SetQuery($query);

		$crumbs	= $db->loadAssoc();	// L�dt Resultat als Array (_data['id'])

		return $crumbs;
	}
}