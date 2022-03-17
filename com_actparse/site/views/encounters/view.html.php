<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

/**
 * HTML View class for the actparse Component
 */
class ActparseViewEncounters extends JViewLegacy
{
	function display($tpl = null)
	{
		$this->state  = $this->get('State');
		$this->params = $this->state->get('params');
		$hide_parse   = $this->params->get('hide_parse', 0);

		// Check if User is logged in if that parameter is set in Backend
		if ($hide_parse)
		{
			$user = JFactory::getUser();

			if ($user->guest == 1)
			{
				$app    = JFactory::getApplication();
				$uri    = JUri::getInstance();
				$return = $uri->toString();
				$url    = 'index.php?option=com_user&view=login&return=' . base64_encode($return);

				$app->enqueueMessage('You must login first');
				$app->redirect($url);
			}
		}

		// Get some data from the models
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->cols       = (array) $this->params->get('enccolumns');

		// Get additional stuff if single raid
		if ($this->state->get('raid.id'))
		{
			// Populate ALL Encounter if set
			if ($this->params->get('show_all'))
			{
				$item = $this->get('All');

				if ($item)
				{
					$item->title = Text::_($this->params->get('name_all', 'COM_ACTPARSE_SAVE_FIRST'));
					$item->all   = true;
					array_unshift($this->items, $item);
				}
			}
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		// Title aus dem Array rauslöschen, da sowieso obligatorisch angezeigt
		$key = array_search('title', $this->cols);

		if ($key !== false)
		{
			unset ($this->cols[$key]);
		}

		$this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));
		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app = JFactory::getApplication();

		// Set Page Header if not already set in the menu entry
		$menus = $app->getMenu();
		$menu  = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $menu->title);
		}
		else
		{
			$this->params->def('page_heading', Text::_('COM_ACTPARSE_ALL_ENCOUNTERS'));
		}

		// Set Pagetitle
		if (!$menu)
		{
			$title = Text::_('COM_ACTPARSE_ALL_ENCOUNTERS');
		}
		else
		{
			$title = $this->params->get('page_title', '');
		}

		if ($app->get('sitename_pagetitles', 0))
		{
			$title = Text::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}

		$this->document->setTitle($title);

		// Set MetaData from menu entry if available
		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetaData('keywords', $this->params->get('menu-meta_keywords'));
		}

		// Set Enviroment Variables (Breadcrumbs)
		$showpath       = $this->params->get('show_ext_path');
		$crumbs         = $this->get('Crumbs');
		$activeMenuView = $menu ? $menu->query['view'] : '';
		$path           = $app->getPathway();

		if ($this->state->get('raid.id'))
		{
			$this->subtitle = Text::_('COM_ACTPARSE_ENCOUNTERS_FROM_RAID') . ' "' . $crumbs['raidname']
							. ' (' . HtmlHelper::_('date', $crumbs['date'], Text::_('DATE_FORMAT_LC4'), 'UTC') . ')"';

			if ($showpath)
			{
				if ($activeMenuView == 'raids')
				{
					$path->addItem($crumbs['raidname']);
				}
			}
			else
			{
				if ($activeMenuView == 'raids')
				{
					$path->addItem(Text::_('COM_ACTPARSE_RAID'));
				}
			}
		}
		else
		{
			$this->subtitle = Text::_('COM_ACTPARSE_ALL_ENCOUNTERS');
		}
	}
}
