<?php
/* Thomas Hunziker - www.bakual.ch - Januar 2010 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class ActparseController extends JController
{
	function display()
	{
		// Setzt einen Standard view 
		if ( ! JRequest::getCmd( 'view' ) ) {
			JRequest::setVar('view', 'encounters' );
		}
		require_once JPATH_COMPONENT.'/helpers/actparse.php';

		parent::display();

		// Load the submenu.
		$view = JRequest::getWord('view', 'encounters');
		ActparseHelper::addSubmenu($view);

		return $this;
	}
}