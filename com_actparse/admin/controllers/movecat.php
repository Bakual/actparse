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

class ActparseControllerMovecat extends JController
{
	function display()
	{
		JRequest::setVar('view', 'movecat');
		parent::display();
	}

	function cancel()
	{
		$this->setRedirect('index.php?option=com_actparse&view=encounters', JText::_('COM_ACTPARSE_OPERATION_CANCELED'));
	}

	function move()
	{
		$app = JFactory::getApplication();

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$db 	=& JFactory::getDBO();
		$user 	=& JFactory::getUser();

		$cid	= JRequest::getVar( 'cid', array(), '', 'array' );
		$post	= JRequest::get( 'post' );
		$catid	= JRequest::getInt('catid');
		JArrayHelper::toInteger($cid);

		$cids = implode( ',', $cid );

		$query = 'UPDATE encounter_table'
		. ' SET catid = ' . (int) $catid
		. ' WHERE id IN ( '. $cids .' )'
		. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
		;

		$db->setQuery( $query );
		if (!$db->query())
		{
			JError::raiseError(500, $db->getErrorMsg() );
		}

		$app->redirect( 'index.php?option=com_actparse&view=encounters' );
	}
}