<?php
/**
 * @package     ACT Parse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

// Register Helperclass for autoloading
JLoader::register('ActparseHelper', JPATH_COMPONENT . '/helpers/actparse.php');

$controller = JControllerLegacy::getInstance('Actparse');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
