<?php
/* Thomas Hunziker - www.bakual.net - Januar 2010 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * Act Parse Component Controller
 */
class ActparseController extends JController
{
	function display()
	{
		// Setzt einen Standard view 
		if ( ! JRequest::getCmd( 'view' ) ) {
			JRequest::setVar('view', 'raids' );
		}
		parent::display();
	}
}

