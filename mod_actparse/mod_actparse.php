<?php
/* Thomas Hunziker - www.bakual.ch - Januar 2010 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

$rows = modactparseHelper::getactparse($params);

require JModuleHelper::getLayoutPath('mod_actparse', $params->get('layout', 'default'));