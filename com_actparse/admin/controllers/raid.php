<?php
/* Thomas Hunziker - www.bakual.net - Januar 2010 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controllerform');

class ActparseControllerRaid extends JControllerForm
{
	protected function allowAdd($data = array())
	{
		return true;
	}

	protected function allowEdit($data = array(), $key = 'id')
	{
		return true;
	}
}