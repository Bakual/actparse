<?php
/**
 * @package     Actparse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

/**
 * AHelper to create Graphlib graph
 *
 * @since  2.0
 */
class ActparseHelperGraph
{
	/**
	 * createGraph
	 *
	 * @param   array   $items   Array ob objects
	 * @param   string  $order   The current ordering
	 * @param   string  $alt     USe enc/title instead of name
	 *
	 * @return  boolean/string  Either the base64 encoded graph image or false
	 */
	public static function createGraph($items, $order, $alt = false)
	{
		if ($order == 'starttime' || $order == 'endtime')
		{
			return false;
		}

		$graphitems = array();

		foreach ($items as $row)
		{
			if ($alt)
			{
				$graphitems['(' . $row->encid . ') ' . $row->title] = $row->$order;
			}
			else
			{
				$graphitems[$row->name] = $row->$order;
			}
		}

		if (!count($graphitems) || (!array_sum($graphitems)))
		{
			return false;
		}

		// Set up Graph
		require_once JPATH_COMPONENT . '/graphlib/phpgraphlib.php';
		$graph = new PHPGraphLib(650, 400);

		// Settings
		if ($order == 'healed' || $order == 'exthps' || $order == 'heals')
		{
			$graph->setGradient('0,200,0', '0,100,0');
		}
		else
		{
			$graph->setGradient('200,0,0', '100,0,0');
		}

		$graph->setupXAxis(33, 'white');
		$graph->setupYAxis('','white');
		$graph->setTextColor('white');
		$graph->setBarOutline(false);
		$graph->setGrid(false);
		$graph->setBackgroundColor('122,119,114');

		$graph->addData($graphitems);

		// Catch output
		ob_start();
		$graph->createGraph();
		$image = base64_encode(ob_get_contents());
		ob_end_clean();

		return $image;
	}
}
