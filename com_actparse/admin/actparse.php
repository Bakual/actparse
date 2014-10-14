<?php
/* Thomas Hunziker - www.bakual.net - Januar 2010 */

defined('_JEXEC') or die;

// Register Helperclass for autoloading
JLoader::register('SermonspeakerHelper', JPATH_COMPONENT . '/helpers/sermonspeaker.php');

$controller = JControllerLegacy::getInstance('Sermonspeaker');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
