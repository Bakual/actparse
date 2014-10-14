<?php
/**
 * @package     ACT Parse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class ActparseController extends JControllerLegacy
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