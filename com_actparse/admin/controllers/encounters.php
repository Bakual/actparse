<?php
/* Thomas Hunziker - www.bakual.ch - Januar 2010 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');

class ActparseControllerEncounters extends JControllerAdmin
{
	public function &getModel($name = 'Encounter', $prefix = 'ActparseModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}