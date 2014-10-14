<?php
/**
 * @package     ACT Parse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;


/**
 * Act Parse Component Controller
 */
class ActparseController extends JControllerLegacy
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

