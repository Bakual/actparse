<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

/**
 * Controller class for the ACT Parse Component
 *
 * @since  1.0
 */
class ActparseControllerMovecat extends JControllerLegacy
{
	/**
	 * Typical view method for MVC based architecture
	 *
	 * This function is provide as a default implementation, in most cases
	 * you will need to override it in your own controllers.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JControllerLegacy  A JControllerLegacy object to support chaining.
	 *
	 * @since   12.2
	 */
	public function display($cachable = false, $urlparams = array())
	{
		JFactory::getApplication()->input->set('view', 'movecat');

		parent::display($cachable, $urlparams);
	}

	/**
	 * Cancel
	 *
	 * @return void
	 */
	public function cancel()
	{
		$this->setRedirect('index.php?option=com_actparse&view=encounters', JText::_('COM_ACTPARSE_OPERATION_CANCELED'));
	}

	/**
	 * Move to a category
	 *
	 * @return void
	 */
	public function move()
	{
		// Check for request forgeries
		JSession::checkToken('get') or die(JText::_('JINVALID_TOKEN'));

		$db     = JFactory::getDBO();
		$user   = JFactory::getUser();
		$app    = JFactory::getApplication();
		$jinput = $app->input();

		$rid  = $jinput->getInt('rid');
		$cid  = $jinput->getVar('cid', array(), '', 'array');
		JArrayHelper::toInteger($cid);
		$cids = implode(',', $cid);

		$query = $db->getQuery(true);
		$query->update('`encounter_table`');
		$query->set('catid = ' . (int) $catid);
		$query->where('id IN (' . $cids . ')');
		$query->where('(checked_out = 0 OR (checked_out = ' . (int) $user->get('id') . '))');

		$db->setQuery($query);
		$db->execute();

		$app->redirect('index.php?option=com_actparse&view=encounters');
	}
}
