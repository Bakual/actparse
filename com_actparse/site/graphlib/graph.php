<?php
	// Joomla Umgebung aufbauen
	define( '_JEXEC', 1 );
	// Joomla Basispfad errechnen, /../../.. geht vom aktuellen Filepfad 3 Verzeichnisse zurück
	define( 'JPATH_BASE', realpath(dirname(__FILE__).'/../../..' ));
	require_once ( JPATH_BASE . '/includes/defines.php' );
	require_once ( JPATH_BASE . '/includes/framework.php' );
	$app =& JFactory::getApplication('site');
	$app->initialise();

	// Session auslesen und Daten abholen
	$session  = &JFactory::getSession();
	$items    = $session->get('GraphItems');
	$settings = $session->get('GraphSettings');

	// Graph bauen
	include 'phpgraphlib.php';
	$graph = new PHPGraphLib(650,300);
	$graph->addData($items);

	$graph->setupXAxis(33,"white");
	$graph->setupyAxis("","white");
	$graph->setTextColor("white");
	$graph->setBarOutline(false);
	$graph->setGrid(false);

	if ($settings['Heal'] == '1')
	{
		$graph->setGradient("0,200,0", "0,100,0");
	}
	else
	{
		$graph->setGradient("200,0,0", "100,0,0");
	}

	$graph->setBackgroundColor("122,119,114");

	$graph->createGraph();
