<?php
/* Thomas Hunziker - www.bakual.ch - Januar 2010 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

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