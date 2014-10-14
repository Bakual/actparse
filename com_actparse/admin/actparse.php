<?php
/* Thomas Hunziker - www.bakual.ch - Januar 2010 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// require helper file
JLoader::register('ActparseHelper', dirname(__FILE__).DS.'helpers'.DS.'actparse.php');

// import joomla controller library
jimport('joomla.application.component.controller');

$controller	= JController::getInstance('Actparse');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();