<?php
/* Thomas Hunziker - www.bakual.ch - Januar 2010 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

/**
 *ACT Parser Component Encounters Model
 *
 */
class ActparseModelSearchbydate extends JModelList
{
	protected function getListQuery()
	{
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->authorisedLevels());

		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'et.*, ROUND(`encdps`) AS `encdps`'
			)
		);
		$query->from('`encounter_table` AS et');

		if($this->getState('starttime'))
			$query->where('et.starttime >= "'.$db->escape($this->getState('starttime')).'"');
		if($this->getState('endtime'))
			$query->where('et.endtime <= "'.$db->escape($this->getState('endtime')).'"');
		if($this->getState('zone'))
			$query->where('et.zone LIKE "'.$db->escape($this->getState('zone')).'"');
		if($this->getState('title'))
			$query->where('et.title LIKE "'.$db->escape($this->getState('title')).'"');

		// Filter by state
		$state = $this->getState('filter.state');
		if (is_numeric($state)) {
			$query->where('et.published = '.(int) $state);
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'starttime')).' '.$db->getEscaped($this->getState('list.direction', 'DESC')));

		$query->limit('50');

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

		$limit = '50';
		$this->setState('list.limit', $limit);

		$limitstart = JRequest::getInt('limitstart', 0);
		$this->setState('list.start', $limitstart);

		$orderCol	= JRequest::getCmd('filter_order', $params->get('default_order', 'starttime'));
		$this->setState('list.ordering', $orderCol);

		$listOrder	=  JRequest::getCmd('filter_order_Dir', $params->get('default_order_dir', 'DESC'));
		$this->setState('list.direction', $listOrder);

		$starttime = JRequest::getString('starttime', '');
		$this->setState('starttime', $starttime);

		$endtime = JRequest::getString('endtime', '');
		$this->setState('endtime', $endtime);

		$title = JRequest::getString('title', '');
		$this->setState('title', $title);

		$zone = JRequest::getString('zone', '');
		$this->setState('zone', $zone);

		if ($starttime || $endtime || $title || $zone)
			$this->setState('show_result', true);

		$this->setState('filter.state',	1);

		// Load the parameters.
		$this->setState('params', $params);
	}

	function getZoneList()
	{
		$db			= JFactory::getDBO();
		$query	= "SELECT DISTINCT zone as value, zone as text \n"
				. "FROM encounter_table \n"
				. "WHERE published = 1 \n"
				. "ORDER BY zone ASC";

		$data = $this->_getList( $query );
		return $data;
	}

	function getTitleList()
	{
		$db			= JFactory::getDBO();
		$zone 		= $this->getState('zone');
		$where = $zone ? "AND zone LIKE '".$db->escape($zone)."' \n" : '';

		$query	= "SELECT DISTINCT title as value, title as text \n"
				. "FROM encounter_table \n"
				. "WHERE published = 1 \n"
				.$where
				. "ORDER BY title ASC";
		$data = $this->_getList( $query );
		return $data;
	}
}