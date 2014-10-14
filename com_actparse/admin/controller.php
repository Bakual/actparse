<?php
/**
 * @package     ACT Parse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

class ActparseController extends JControllerLegacy
{
	protected $default_view = 'encounters';

	function display()
	{
		parent::display();

		return $this;
	}
}