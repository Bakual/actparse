<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

class modactparseHelper
{
	function getactparse(&$params)
	{
		$itemid		= (int)$params->get('menuitem');
		$limit		= $params->get('actparse_limit');
		$orderby	= $params->get('actparse_order');

		$db			= JFactory::getDBO();
		$query	= "SELECT * \n"
				. "FROM #__actparse_raids \n"
				. "WHERE published = 1 \n"
				. "ORDER BY ".$orderby." \n"
				. "LIMIT ".$limit;

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		return $rows;
	}
}
