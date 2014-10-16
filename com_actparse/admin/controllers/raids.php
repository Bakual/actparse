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
class ActparseControllerRaids extends JControllerAdmin
{
	public function &getModel($name = 'Raid', $prefix = 'ActparseModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}
