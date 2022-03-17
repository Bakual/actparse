<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;

/**
 * Controller class for the ACT Parse Component
 *
 * @since  1.0
 */
class ActparseControllerRaid extends FormController
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
