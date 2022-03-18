<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

// Load Composer Autoloader
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/vendor/autoload.php');

$controller = BaseController::getInstance('Actparse');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
