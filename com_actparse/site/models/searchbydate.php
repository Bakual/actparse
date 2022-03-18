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

/**
 *ACT Parser Component Encounters Model
 *
 */
class ActparseModelSearchbydate extends JModelList
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
				'et.*, ROUND(`encdps`) AS `encdps`'
			)
		);
		$query->from('`encounter_table` AS et');

		if($this->getState('starttime'))
		{
			$query->where('et.starttime >= "' . $db->escape($this->getState('starttime')) . '"');
		}

		if($this->getState('endtime'))
		{
			$query->where('et.endtime <= "' . $db->escape($this->getState('endtime')) . '"');
		}

		if($this->getState('zone'))
		{
			$query->where('et.zone LIKE "' . $db->escape($this->getState('zone')) . '"');
		}

		if($this->getState('title'))
		{
			$query->where('et.title LIKE "' . $db->escape($this->getState('title')) . '"');
		}

		// Filter by state
		$state = $this->getState('filter.state');

		if (is_numeric($state))
		{
			$query->where('et.published = ' . (int) $state);
		}

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'starttime')) . ' ' . $db->escape($this->getState('list.direction', 'DESC')));

		$query->limit('50');

		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since 1.0
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

		$limit = '50';
		$this->setState('list.limit', $limit);

		$limitstart = $jinput->getInt('limitstart', 0);
		$this->setState('list.start', $limitstart);

		$orderCol	= $jinput->getCmd('filter_order', $params->get('default_order', 'starttime'));
		$this->setState('list.ordering', $orderCol);

		$listOrder	=  $jinput->getCmd('filter_order_Dir', $params->get('default_order_dir', 'DESC'));
		$this->setState('list.direction', $listOrder);

		$starttime = $jinput->getString('starttime', '');
		$this->setState('starttime', $starttime);

		$endtime = $jinput->getString('endtime', '');
		$this->setState('endtime', $endtime);

		$title = $jinput->getString('title', '');
		$this->setState('title', $title);

		$zone = $jinput->getString('zone', '');
		$this->setState('zone', $zone);

		if ($starttime || $endtime || $title || $zone)
		{
			$this->setState('show_result', true);
		}

		$this->setState('filter.state', 1);

		// Load the parameters.
		$this->setState('params', $params);
	}

	function getZonelist()
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);
		$query->select('DISTINCT zone as value, zone as text');
		$query->from('encounter_table');
		$query->where('published = 1');
		$query->order('zone ASC');

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	function getTitlelist()
	{
		$db = Factory::getDBO();

		$query = $db->getQuery(true);
		$query->select('DISTINCT title as value, title as text');
		$query->from('encounter_table');
		$query->where('published = 1');
		$query->order('title ASC');

		if ($zone = $this->getState('zone'))
		{
			$query->where('zone LIKE ' . $db->quote($db->escape($zone)));
		}

		$db->setQuery($query);

		return $db->loadObjectList();
	}
}
