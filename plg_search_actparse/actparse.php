<?php
/**
 * @package     ACTParse
 * @subpackage  Plugin.Search
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   © 2023 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

class plgSearchActparse extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * @return array An array of search areas
	 */
	function onContentSearchAreas()
	{
		static $areas = array(
			'actparse' => 'PLG_SEARCH_ACTPARSE_ENCOUNTERS'
		);

		return $areas;
	}

	function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		$text = trim($text);

		if ($text == '')
		{
			return array();
		}

		if (is_array($areas))
		{
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas())))
			{
				return array();
			}
		}

		$title     = $this->params->get('actparse_title', 1);
		$zone      = $this->params->get('actparse_zone', 1);
		$starttime = $this->params->get('actparse_starttime', 1);
		$limit     = $this->params->def('actparse_limit', 50);
		$itemid    = $this->params->def('menuitem', 50);

		$db    = Factory::getDBO();
		$query = $db->getQuery(true);

		$query->select('et.encid, et.title, et.starttime as created, et.endtime, et.zone as section, et.encdps, "2" AS browsernav');
		$query->from('encounter_table AS et');
		$query->where('et.published = 1');

		$wheres = array();

		switch ($phrase)
		{
			case 'exact':
				$text   = $db->quote('%' . $db->escape($text, true) . '%', false);

				if ($title)
				{
					$wheres[] = "LOWER(et.title) LIKE " . $text;
				}

				if ($zone)
				{
					$wheres[] = "LOWER(et.zone) LIKE " . $text;
				}

				if ($starttime)
				{
					$wheres[] = "LOWER(et.starttime) LIKE " . $text;
				}

				$query->where('(' . implode(') OR (', $wheres) . ')');

				break;

			case 'all':
			case 'any':
			default:
				$words  = explode(' ', $text);

				foreach ($words as $word)
				{
					$word    = $db->quote('%' . $db->escape($word, true) . '%', false);
					$wheres2 = array();

					if ($title)
					{
						$wheres2[] = 'LOWER(et.title) LIKE ' . $word;
					}

					if ($zone)
					{
						$wheres2[] = 'LOWER(et.zone) LIKE ' . $word;
					}

					if ($starttime)
					{
						$wheres2[] = 'LOWER(et.starttime) LIKE ' . $word;
					}

					$wheres[] = implode(' OR ', $wheres2);
				}

				$query->where('(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')');

				break;
		}

		switch ($ordering)
		{
			case 'oldest':
				$query->order('et.starttime ASC');

				break;

			case 'newest':
				$query->order('et.starttime DESC');

				break;

			case 'category':
				$query->order('et.zone ASC');

				break;

			case 'popular':
				$query->order('et.encdps DESC');

				break;

			case 'alpha':
			default:
				$query->order('et.title ASC');

				break;
		}

		$db->setQuery($query, 0, $limit);
		$list = $db->loadObjectList();

		if (isset($list))
		{
			foreach($list as $key => $item)
			{
				$list[$key]->href = JRoute::_('index.php?option=com_actparse&view=combatants&encid=' . $item->encid . '&Itemid=' . $itemid);
				$list[$key]->text = $item->title . ': ' . HtmlHelper::_('date', $item->created, 'Y-m-d H:m:s', 'UTC');
			}
		}

		return $list;
	}
}
