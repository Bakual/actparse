<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

/**
 * Model class for handling lists of encounters.
 *
 * @since  1.0
 */
class ActparseModelEncounters extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     BaseDatabaseModel
	 * @since   1.0
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
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

		// Searchtools
		$config['filter_fields'][] = 'category_id';
		$config['filter_fields'][] = 'level';
		$config['filter_fields'][] = 'zone';
		$config['filter_fields'][] = 'raid';

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		parent::populateState('encounters.title', 'asc');
	}

	/**
	 * Method to get a store id based on the model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  An identifier string to generate the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.0
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.zone');
		$id .= ':' . $this->getState('filter.category_id');

		return parent::getStoreId($id);
	}

	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  \Joomla\Database\QueryInterface   A JDatabaseQuery object to retrieve the data set.
	 *
	 * @since   1.0
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'encounters.id, encounters.title, encounters.catid, '
				. 'encounters.starttime, encounters.rid, encounters.encid, '
				. 'encounters.published, encounters.zone'
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
		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('encounters.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(encounters.published IN (0, 1))');
		}

		// Filter by zone
		$zone = $this->getState('filter.zone');

		if ($zone)
		{
			$query->where('encounters.zone = ' . $db->quote($db->escape($zone)));
		}

		// Filter by raid
		$raid = $this->getState('filter.raid');

		if ($raid)
		{
			$query->where('encounters.rid = ' . (int) $raid);
		}

		// Filter by category.
		$baselevel  = 1;
		$categoryId = $this->getState('filter.category_id');

		if (is_numeric($categoryId))
		{
			$cat_tbl = Table::getInstance('Category', 'JTable');
			$cat_tbl->load($categoryId);
			$rgt = $cat_tbl->rgt;
			$lft = $cat_tbl->lft;
			$baselevel = (int) $cat_tbl->level;
			$query->where('c.lft >= ' . (int) $lft)
				->where('c.rgt <= ' . (int) $rgt);
		}

		// Filter on the level.
		if ($level = $this->getState('filter.level'))
		{
			$query->where('c.level <= ' . ((int) $level + (int) $baselevel - 1));
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('encounters.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('(encounters.title LIKE ' . $search . ')');
			}
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');
		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * Method to get a list of zones.
	 *
	 * @return  array    An array of results.
	 *
	 * @since   1.0
	 */
	public function getZones()
	{
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select('DISTINCT `zone`')
			->from('`encounter_table`')
			->order('`zone` ASC');

		return $this->_getList($query);
	}

	/**
	 * Method to get a list of raids (for the batch modal)
	 *
	 * @return  array    An array of results.
	 *
	 * @since   1.0
	 */
	public function getRaids()
	{
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('id AS value, raidname, date');
		$query->from('#__actparse_raids');
		$query->order('raidname ASC');
		$db->setQuery($query);

		$raids = $db->loadObjectList();

		foreach ($raids as $raid)
		{
			$raid->text = $raid->raidname . ' (' . JHTML::Date($raid->date, JText::_('DATE_FORMAT_LC4'), 'UTC') . ')';
		}

		return $raids;
	}

	/**
	 * Check for the presence of encounter_table.
	 *
	 * @return  boolean   True if the table exists, false otherwise.
	 *
	 * @since   2.0
	 */
	public function getSanityEncountersTable()
	{
		$db     = Factory::getDbo();
		$tables = $db->getTableList();

		return in_array('encounter_table', $tables);
	}

	/**
	 * Check for the presence of the additional fields in encounter_table and add if needed.
	 *
	 * @return  boolean.
	 *
	 * @since   2.0
	 */
	public function getSanityEncountersFields()
	{
		$db     = Factory::getDbo();
		$fields = $db->getTableColumns('encounter_table');

		// Add needed fields to table.
		if (is_array($fields) && !array_key_exists('catid', $fields))
		{
			$fieldlist = array('id', 'catid', 'rid', 'checked_out', 'checked_out_time', 'published');

			$query = "ALTER TABLE `encounter_table` \n"
				. "CHANGE `starttime` `starttime` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00', \n"
				. " ADD `id` int(11) NOT NULL AUTO_INCREMENT, \n"
				. " ADD `catid` int(11) NOT NULL DEFAULT '0', \n"
				. " ADD `rid` int(11), \n"
				. " ADD `checked_out` int(11) NOT NULL, \n"
				. " ADD `checked_out_time` datetime NOT NULL, \n"
				. " ADD `published` tinyint(1) NOT NULL DEFAULT 1, \n"
				. " ADD PRIMARY KEY (`id`)";
			$db->setQuery($query);

			if (!$db->execute())
			{
				return false;
			}
		}

		return true;
	}
}
