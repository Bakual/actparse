<?php
/* Thomas Hunziker - www.bakual.ch - Januar 2010 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// laden des Joomla! Basis Controllers
require_once (JPATH_COMPONENT.DS.'controller.php');

// Einlesen weiterer Controller
if($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}

// Einen eigenen Controller erzeugen
$classname	= 'ActparseController'.$controller;
$controller = new $classname( );

// Nachsehen, ob Parameter angekommen sind (Requests)
$controller->execute( JRequest::getVar('task'));

// Umleitung innerhalb des Controllers
$controller->redirect();