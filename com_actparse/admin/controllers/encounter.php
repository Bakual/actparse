<?php
/**
 * @package     ACT Parse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

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
}