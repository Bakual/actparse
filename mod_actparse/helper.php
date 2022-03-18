<?php
/**
 * @package     ACTParse
 * @subpackage  Module
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class modActparseHelper
{
	public static function getActparse(&$params)
	{
		$limit   = $params->get('actparse_limit');
		$orderby = $params->get('actparse_order');

		$db    = Factory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__actparse_raids');
		$query->where('published = 1');
		$query->order($db->escape($orderby));
		$query->limit((int) $limit);

		$db->setQuery($query);

		return $db->loadObjectList();
	}
}
