<?php
/**
 * @package     ACT Parse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class ActparseControllerEncounters extends JControllerAdmin
{
	public function &getModel($name = 'Encounter', $prefix = 'ActparseModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}