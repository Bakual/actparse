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

class ActparseModelCurrent extends JModelList
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
				'cut.*, ROUND(`encdps`) AS `encdps`, ROUND(`enchps`) AS `enchps`, ROUND(`dps`) AS `dps`'
			)
		);
		$query->from('`current_table` AS cut');

		// Filter by PC/NPC
		if ($show_npc = $this->getState('show_npc')) {
			$query->where('cut.ally = "'.$show_npc.'"');
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

		$this->setState('filter.state',	1);

		// Load the parameters.
		$this->setState('params', $params);
	}
}