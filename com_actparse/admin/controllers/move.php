<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;


class ActparseControllerMove extends JControllerLegacy
{
	function display()
	{
		JRequest::setVar('view', 'move');
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
		$rid	= JRequest::getInt('rid');
		JArrayHelper::toInteger($cid);

		$cids = implode( ',', $cid );

		$query = 'UPDATE encounter_table'
		. ' SET rid = ' . (int) $rid
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
