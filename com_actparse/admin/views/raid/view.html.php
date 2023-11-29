<?php
/**
 * @package         ACTParse
 * @subpackage      Component.Administrator
 * @author          Thomas Hunziker <admin@bakual.net>
 * @copyright   © 2023 - Thomas Hunziker
 * @license         http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

class ActparseViewRaid extends HtmlView
{
	protected $item;
	protected $form;

	function display($tpl = null)
	{
		$this->item = $this->get('Item');
		$this->form = $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->addToolbar();

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', true);

		$isNew = ($this->item->id == 0);

		ToolBarHelper::title(Text::_('COM_ACTPARSE_' . $isNew ? 'ADD' : 'EDIT' . 'RAID'), 'drawer');

		// Build the actions for new and existing records.
		if ($isNew)
		{
			ToolBarHelper::apply('raid.apply');
			ToolBarHelper::save('raid.save');
			ToolbarHelper::save2new('raid.save2new');
			ToolBarHelper::cancel('raid.cancel');
		}
		else
		{
			ToolBarHelper::apply('raid.apply');
			ToolBarHelper::save('raid.save');
			ToolbarHelper::save2new('raid.save2new');
			ToolbarHelper::save2new('raid.save2copy');
			ToolBarHelper::cancel('raid.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
