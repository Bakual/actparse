<?php
/**
 * @package     ACTParse
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

		// Filter by search in title or scripture (with ref:)
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
			$query->where('(et.title LIKE '.$search.')');
		}

		// Join over et Category.
		$query->join('LEFT', '#__categories AS c_et ON c_et.id = et.catid');
		if ($categoryId = $this->getState('category.id')) {
			$query->where('et.catid = '.(int) $categoryId);
		}
		$query->where('(et.catid = 0 OR (c_et.access IN ('.$groups.') AND c_et.published = 1))');

		// Filter by Raid
		if ($raidId = $this->getState('raid.id')) {
			$query->where('et.rid = '.(int) $raidId);
			$query->where('et.title <> "All"');
		}

		// Filter by state
		$state = $this->getState('filter.state');
		if (is_numeric($state)) {
			$query->where('et.published = '.(int) $state);
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'starttime')).' '.$db->getEscaped($this->getState('list.direction', 'DESC')));

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

		$orderCol	= JRequest::getCmd('filter_order', $params->get('default_order', 'starttime'));
		$this->setState('list.ordering', $orderCol);

		$listOrder	=  JRequest::getCmd('filter_order_Dir', $params->get('default_order_dir', 'DESC'));
		$this->setState('list.direction', $listOrder);

		$id = (int)$params->get('enc_cat', 0);
		if (!$id){ $id = JRequest::getInt('enc_cat', 0); }
		$this->setState('category.id', $id);

		$id = (int)$params->get('enc_rid', 0);
		if (!$id){ $id = JRequest::getInt('enc_rid', 0); }
		$this->setState('raid.id', $id);

		$this->setState('filter.state',	1);

		// Load the parameters.
		$this->setState('params', $params);
	}

	function getAll()
	{
		$db		= JFactory::getDBO();

		$select = 'et.*';
		$from	= 'encounter_table AS et';

		$wheres[]	= 'et.rid = "' .$this->getState('raid.id'). '"';
		$wheres[]	= 'et.title = "All"';

		$query	= "SELECT et.* \n"
				. "FROM encounter_table AS et \n"
				. "WHERE et.rid = '".$this->getState('raid.id')."' \n"
				. "AND et.title = 'All' \n"
				. "AND et.published = 1";

		$db->SetQuery($query);

		$this->_all	= $db->loadObject();

        return $this->_all;
	}

	function getCrumbs()
	{
		$db		=& JFactory::getDBO();

		$query	= "SELECT * \n"
				. "FROM #__actparse_raids \n"
				. "WHERE id = '".$this->getState('raid.id')."'";

		$db->SetQuery($query);

		$crumbs	= $db->loadAssoc();	// L�dt Resultat als Array (_query['id'])

		return $crumbs;
	}
}
