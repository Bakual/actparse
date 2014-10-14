<?php
/* Thomas Hunziker - www.bakual.net - Januar 2010 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 *ACT Parser Component Encounter Model
 *
 */
class ActparseModelMove extends JModel
{
	function __construct()
	{
		parent::__construct();
		
	}
	
	function getItems()
	{
		$db		=& JFactory::getDBO();
		$cid 	= JRequest::getVar( 'cid', array(0), '', 'array' );
		$cids = implode(',', $cid);

		// ausgew�hlte Encounter auslesen
		$query = 'SELECT et.*, rt.raidname, rt.date'
		. ' FROM encounter_table AS et'
		. ' LEFT JOIN #__actparse_raids AS rt ON rt.id = et.rid'
		. ' WHERE et.id IN ( '. $cids .' )'
		;

		$items	= $this->_getList($query);
		return $items;
	}

	function getRaids()
	{
		$query	= "SELECT raidname, date, id as value \n"
				. "FROM #__actparse_raids \n"
				. "ORDER BY raidname ASC";

		$raids	= $this->_getList($query);

		foreach ($raids as $row) {
			$row->text	= $row->raidname.' ('.JHtml::Date($row->date, JText::_('DATE_FORMAT_LC4'), 'UTC') . ')';
		}
		
        return $raids;
	}
}