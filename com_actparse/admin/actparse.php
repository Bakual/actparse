<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   © 2022 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

// Register Helperclass for autoloading
JLoader::register('ActparseHelper', JPATH_COMPONENT . '/helpers/actparse.php');

$controller = BaseController::getInstance('Actparse');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
