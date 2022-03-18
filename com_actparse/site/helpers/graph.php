<?php
/**
 * @package     Actparse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use pChart\pColor;
use pChart\pDraw;
use pChart\pCharts;

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
echo '<pre>' . print_r($graphitems) . '</pre>';
		// Set up Graph
		$graph = new pDraw(700, 230);


/* Populate the pData object */
		$graph->myData->addPoints([150,220,300,-250,-420,-200,300,200,100],"Server A");
		$graph->myData->addPoints([140,0,340,-300,-320,-300,200,100,50],"Server B");
		$graph->myData->setAxisName(0,"Hits");
		$graph->myData->addPoints(["January","February","March","April","May","June","July","August","September"],"Months");
		$graph->myData->setSerieDescription("Months","Month");
		$graph->myData->setAbscissa("Months");

/* Turn off Anti-aliasing */
		$graph->Antialias = FALSE;

/* Add a border to the picture */
		$graph->drawRectangle(0,0,699,229,["Color"=>new pColor(0,0,0)]);

/* Set the default font */
		$graph->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

/* Define the chart area */
		$graph->setGraphArea(60,40,650,200);

/* Draw the scale */
		$graph->drawScale(["GridColor"=>new pColor(200,200,200),"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE]);

/* Write the chart legend */
		$graph->drawLegend(580,12,["Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL]);

/* Turn on shadow computing */
		$graph->setShadow(TRUE,["X"=>1,"Y"=>1,"Color"=>new pColor(0,0,0,10)]);

/* Draw the chart */
(new pCharts($graph))->drawBarChart([
	"Gradient"=>TRUE,
	"GradientMode"=>GRADIENT_EFFECT_CAN,
	"DisplayPos"=>LABEL_POS_INSIDE,
	"DisplayValues"=>TRUE,
	"DisplayColor"=>new pColor(255,255,255),
	"DisplayShadow"=>TRUE,
	"Surrounding"=>10
]);

/* Render the picture (choose the best way) */
		$graph->autoOutput("temp/example.drawBarChart.simple.png");

		// Settings
/*		if ($order == 'healed' || $order == 'exthps' || $order == 'heals')
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
*/	}
}
