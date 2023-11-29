<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   © 2023 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/**
 *ACT Parser Component Raids Model
 *
 */
class ActparseModelRaids extends JModelLegacy
{
	function _buildQuery()
	{
		$db = Factory::getDBO();

		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('`#__actparse_raids`');
		$query->where('published = 1');
		$query->order('raidname ASC');

		return $query;
	}

	function getData($options = array())
	{
		$query = $this->_buildQuery($options);

		return $this->_getList($query);
	}
}
