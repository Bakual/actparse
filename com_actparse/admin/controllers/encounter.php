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
class ActparseControllerEncounter extends JControllerForm
{
	protected function allowAdd($data = array())
	{
		return true;
	}

	protected function allowEdit($data = array(), $key = 'id')
	{
		return true;
	}

	/**
	 * Method to run batch operations.
	 *
	 * @param   object  $model  The model.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   2.0
	 */
	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model = $this->getModel('Encounter', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_actparse&view=encounters' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}
}
