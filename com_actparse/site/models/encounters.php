<?php
/**
 * @package         ACTParse
 * @subpackage      Component.Site
 * @author          Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license         http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;

/**
 *ACT Parser Component Encounters Model
 *
 */
class ActparseModelEncounters extends ListModel
{
	protected function getListQuery()
	{
		$groups = implode(',', Factory::getUser()->getAuthorisedViewLevels());

		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

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

		if (!empty($search))
		{
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where('(et.title LIKE ' . $search . ')');
		}

		// Join over et Category.
		$query->join('LEFT', '#__categories AS c_et ON c_et.id = et.catid');

		if ($categoryId = $this->getState('category.id'))
		{
			$query->where('et.catid = ' . (int) $categoryId);
		}

		$query->where('(et.catid = 0 OR (c_et.access IN (' . $groups . ') AND c_et.published = 1))');

		// Filter by Raid
		if ($raidId = $this->getState('raid.id'))
		{
			$query->where('et.rid = ' . (int) $raidId);
			$query->where('et.title <> "All"');
		}

		// Filter by state
		$state = $this->getState('filter.state');

		if (is_numeric($state))
		{
			$query->where('et.published = ' . (int) $state);
		}

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'starttime')) . ' ' . $db->escape($this->getState('list.direction', 'DESC')));

		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since    1.6
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

		$orderCol = $jinput->getCmd('filter_order', $params->get('default_order', 'starttime'));
		$this->setState('list.ordering', $orderCol);

		$listOrder = $jinput->getCmd('filter_order_Dir', $params->get('default_order_dir', 'DESC'));
		$this->setState('list.direction', $listOrder);

		$id = (int) $params->get('enc_cat', 0);

		if (!$id)
		{
			$id = $jinput->getInt('enc_cat', 0);
		}

		$this->setState('category.id', $id);

		$id = (int) $params->get('enc_rid', 0);

		if (!$id)
		{
			$id = $jinput->getInt('enc_rid', 0);
		}

		$this->setState('raid.id', $id);

		$this->setState('filter.state', 1);

		// Load the parameters.
		$this->setState('params', $params);
	}

	function getAll()
	{
		$db = Factory::getDBO();

		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('encounter_table');
		$query->where('rid = ' . (int) $this->getState('raid.id'));
		$query->where('title = "All"');
		$query->where('published = 1');

		$db->setQuery($query);

		return $db->loadObject();
	}

	function getCrumbs()
	{
		$db = Factory::getDBO();

		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__actparse_raids');
		$query->where('id = ' . (int) $this->getState('raid.id'));

		$db->setQuery($query);

		return $db->loadAssoc();
	}
}
