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

/**
 *ACT Parser Component Encounters Model
 *
 */
class ActparseModelEncounters extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'encounters.id',
				'title', 'encounters.title',
				'catid', 'encounters.catid', 'category_title',
				'rid', 'encounters.rid', 'raidname',
				'starttime', 'encounters.starttime',
				'encid', 'encounters.encid',
				'published', 'encounters.published',
			);
		}

		parent::__construct($config);
	}

	protected function populateState()
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

		$zone = $app->getUserStateFromRequest($this->context.'.filter.zone', 'filter_zone', '', 'string');
		$this->setState('filter.zone', $zone);

		$categoryId = $app->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id', '');
		$this->setState('filter.category_id', $categoryId);

		// List state information.
		parent::populateState('encounters.title', 'asc');
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.state');
		$id.= ':' . $this->getState('filter.speaker');
		$id.= ':' . $this->getState('filter.category_id');

		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'encounters.id, encounters.title, encounters.catid, '.
				'encounters.starttime, encounters.rid, encounters.encid, '.
				'encounters.published, encounters.zone'
			)
		);
		$query->from('`encounter_table` AS encounters');

		// Join over the categories.
		$query->select('c.title AS category_title');
		$query->join('LEFT', '#__categories AS c ON c.id = encounters.catid');

		// Join over the raids.
		$query->select('raids.raidname, raids.date');
		$query->join('LEFT', '#__actparse_raids AS raids ON raids.id = encounters.rid');

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('encounters.published = '.(int) $published);
		} else if ($published === '') {
			$query->where('(encounters.published IN (0, 1))');
		}

		// Filter by zone
		$zone = $this->getState('filter.zone');
		if ($zone) {
			$query->where('encounters.zone = "'.$zone.'"');
		}

		// Filter by category.
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId)) {
			$query->where('encounters.catid = '.(int) $categoryId);
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('encounters.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('(encounters.title LIKE '.$search.')');
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));

		return $query;
	}

	function getZones()
	{
		$query = 'SELECT DISTINCT zone '
		. ' FROM encounter_table'
		. ' ORDER BY zone ASC'
		;
		
		// Query ausf�hren (mehrzeiliges Resulat als Array)
		$zones	= $this->_getList($query);

        return $zones;
	}
}