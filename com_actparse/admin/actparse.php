<?php
/* Thomas Hunziker - www.bakual.net - Januar 2010 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// require helper file
JLoader::register('ActparseHelper', dirname(__FILE__) . '/helpers/actparse.php');

// import joomla controller library
jimport('joomla.application.component.controller');

$controller	= JController::getInstance('Actparse');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();