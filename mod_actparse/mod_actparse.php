<?php
/**
 * @package     ACTParse
 * @subpackage  Module
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   © 2022 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

$rows = modActparseHelper::getActparse($params);

require JModuleHelper::getLayoutPath('mod_actparse', $params->get('layout', 'default'));
