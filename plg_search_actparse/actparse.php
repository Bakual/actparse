<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgSearchActparse extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

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

	function onContentSearch($text, $phrase='', $ordering='', $areas=null)
	{
		$db		=& JFactory::getDBO();

		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onActparseSearchAreas()))) {
				return array();
			}
		}

		$title		= $this->params->get('actparse_title', 1);
		$zone		= $this->params->get('actparse_zone', 1);
		$starttime	= $this->params->get('actparse_starttime', 1);
		$limit		= $this->params->def('actparse_limit', 50);
		$itemid		= $this->params->def('menuitem', 50);

		$text = trim($text);
		if ($text == '') {
			return array();
		}

		$wheres = array();
		switch ($phrase) {
			case 'exact':
				$text		= $db->Quote('%'.$db->getEscaped($text, true).'%', false);
				$wheres2 	= array();
				if ($title)
					$wheres2[] 	= "LOWER(et.title) LIKE ".$text;
				if ($zone)
					$wheres2[] 	= "LOWER(et.zone) LIKE ".$text;
				if ($starttime)
					$wheres2[] 	= "LOWER(et.starttime) LIKE ".$text;
				$where		= '(' . implode(') OR (', $wheres2) . ')';
				break;

			case 'all':
			case 'any':
			default:
				$words = explode(' ', $text);
				$wheres = array();
				foreach ($words as $word) {
					$word		= $db->Quote('%'.$db->getEscaped($word, true).'%', false);
					$wheres2	= array();
					$wheres2 	= array();
					if ($title)
						$wheres2[] 	= "LOWER(et.title) LIKE ".$word;
					if ($zone)
						$wheres2[] 	= "LOWER(et.zone) LIKE ".$word;
					if ($starttime)
						$wheres2[] 	= "LOWER(et.starttime) LIKE ".$word;
					$wheres[]	= implode(' OR ', $wheres2);
				}
				$where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				break;
		}

		switch ( $ordering )
		{
			case 'oldest':
				$order = 'et.starttime ASC';
				break;

			case 'newest':
				$order = 'et.starttime DESC';
				break;

			case 'category':
				$order = 'et.zone ASC';
				break;

			case 'popular':
				$order = 'et.encdps DESC';
				break;

			case 'alpha':
			default:
				$order = 'et.title ASC';
				break;
		}

		$rows = array();
		$query	= $db->getQuery(true);

		$query->clear();
		$query->select('et.encid, et.title, et.starttime as created, et.endtime, et.zone as section, et.encdps, "2" AS browsernav');
		$query->from('encounter_table AS et');
		$query->where('('.$where.') AND et.published = 1');
		$query->order($order);

		$db->setQuery($query, 0, $limit);
		$list = $db->loadObjectList();
		$limit -= count($list);

		if (isset($list))
		{
			foreach($list as $key => $item)
			{
				$list[$key]->href = JRoute::_('index.php?option=com_actparse&view=combatants&encid='.$item->encid.'&Itemid='.$itemid);
				$list[$key]->text = $item->title.': '.JHtml::_('date', $item->created, 'Y-m-d H:m:s', 'UTC');
			}
		}

		return $list;
	}
}
