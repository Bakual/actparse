<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * HTML View class for the Actparse Component
 *
 * @since  1.0
 */
class ActparseViewinfo extends HtmlView
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 *
	 * @see     HtmlView::loadTemplate()
	 * @since   1.0
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		ToolBarHelper::title(Text::_('COM_ACTPARSE_INFO'), 'info');
		ToolBarHelper::preferences('com_actparse');

		parent::display($tpl);
	}

}
