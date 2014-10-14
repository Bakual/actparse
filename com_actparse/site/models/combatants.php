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

class ActparseModelCombatants extends JModelList
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
				'ct.*, ROUND(`encdps`) AS `encdps`, ROUND(`enchps`) AS `enchps`, ROUND(`dps`) AS `dps`'
			)
		);
		$query->from('`combatant_table` AS ct');
		$query->where('ct.encid = "'.$this->getState('encid').'"');

		// Filter by PC/NPC
		if ($show_npc = $this->getState('show_npc')) {
			$query->where('ct.ally = "'.$show_npc.'"');
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'encdps')).' '.$db->getEscaped($this->getState('list.direction', 'DESC')));

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

		$orderCol	= JRequest::getCmd('filter_order', $params->get('default_order', 'encdps'));
		$this->setState('list.ordering', $orderCol);

		$listOrder	=  JRequest::getCmd('filter_order_Dir', $params->get('default_order_dir', 'DESC'));
		$this->setState('list.direction', $listOrder);

		$show_npc	= JRequest::getWord('show_npc', 0);
		$this->setState('show_npc', $show_npc);

		$encid		= JRequest::getCmd('encid');
		$this->setState('encid', $encid);

		$this->setState('filter.state',	1);

		// Load the parameters.
		$this->setState('params', $params);
	}

	function getCrumbs()
	{
		$db		= JFactory::getDBO();

		$query	= "SELECT rt.raidname, et.rid, et.title as encname \n"
				. "FROM encounter_table AS et \n"
				. "LEFT JOIN #__actparse_raids AS rt ON et.rid = rt.id \n"
				. "WHERE et.encid = '".$this->getState('encid')."'";

		$db->SetQuery($query);

		$crumbs	= $db->loadAssoc();	// L�dt Resultat als Array (_data['id'])

		return $crumbs;
	}
}