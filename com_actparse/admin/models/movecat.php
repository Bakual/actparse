<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

/**
 *ACT Parser Component Encounter Model
 *
 */
class ActparseModelMovecat extends JModel
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
		$query = 'SELECT et.*, cat.title AS category'
		. ' FROM encounter_table AS et'
		. ' LEFT JOIN #__categories AS cat ON cat.id = et.catid'
		. ' WHERE et.id IN ( '. $cids .' )'
		;

		$items	= $this->_getList($query);

	return $items;
	}
}
