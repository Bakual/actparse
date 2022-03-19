<?php
/**
 * @package         Actparse
 * @subpackage      Component.Site
 * @author          Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license         http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

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
	 * @param   array   $items  Array ob objects
	 * @param   string  $order  The current ordering
	 * @param   string  $alt    USe enc/title instead of name
	 *
	 * @return  boolean
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
				$graphitems['(' . $row->encid . ') ' . $row->title] = (int) $row->$order;
			}
			else
			{
				$graphitems[$row->name] = (int) $row->$order;
			}
		}

		if (!count($graphitems) || (!array_sum($graphitems)))
		{
			return false;
		}

		if ($order == 'healed' || $order == 'exthps' || $order == 'heals')
		{
			$bgcolor     = 'rgba(0,200,0,0.1)';
			$bordercolor = 'rgba(0,200,0,1)';
		}
		else
		{
			$bgcolor     = 'rgba(200,0,0,0.1)';
			$bordercolor = 'rgba(200,0,0,1)';
		}

		HtmlHelper::_('script', 'com_actparse/chart/chart.min.js', array('relative' => true));

		$script = 'document.addEventListener("DOMContentLoaded", function () {
			const ctx = document.getElementById("actChart");
			const myChart = new Chart(ctx, {
				type: "bar",
				data: {
					datasets: [{
						label: "' . Text::_('COM_ACTPARSE_' . $order) . '",
						data: ' . json_encode($graphitems) . ',
						backgroundColor: "' . $bgcolor . '",
						borderColor: "' . $bordercolor . '",
						borderWidth: 1,
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						y: {
							beginAtZero: true
						}
					}
				}
			});
		});
		';

		Factory::getApplication()->getDocument()->addScriptDeclaration($script);
		Factory::getApplication()->getDocument()->addStyleDeclaration('
		#actChart-container {
			position: relative;
			height:40vh;
			width:80vw"
		}
		');

		return true;
	}
}
