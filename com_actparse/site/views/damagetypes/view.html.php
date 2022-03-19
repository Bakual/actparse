<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Uri\Uri;

/**
 * HTML View class for the actparse Component
 */
class ActparseViewDamagetypes extends HtmlView
{
	function display($tpl = null)
	{
		$app          = Factory::getApplication();
		$this->state  = $this->get('State');
		$this->params = $this->state->get('params');

		$hide_parse = $this->params->get('hide_parse', 0);

		// Check if User is logged in if that parameter is set in Backend
		if ($hide_parse)
		{
			$user = Factory::getUser();

			if ($user->guest == 1)
			{
				$uri    = Uri::getInstance();
				$return = $uri->toString();
				$url    = 'index.php?option=com_user&view=login&return=' . base64_encode($return);

				$app->enqueueMessage('You must login first');
				$app->redirect($url);
			}
		}

		// Get some data from the models
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->cols = (array) $this->params->get('damagecolumns');

		// Type aus dem Array rauslöschen, da sowieso obligatorisch angezeigt
		$key = array_search('type', $this->cols);

		if ($key !== false)
		{
			unset ($this->cols[$key]);
		}

		$this->showgraph = $this->params->get('show_graph');

		if ($this->showgraph)
		{
			require_once JPATH_COMPONENT . '/helpers/graph.php';
			$this->showgraph = ActparseHelperGraph::createGraph($this->items, $this->state->get('list.ordering'), false, 'type');
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
		$app = Factory::getApplication();

		// Set Page Header if not already set in the menu entry
		$menus = $app->getMenu();
		$menu  = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $menu->title);
		}
		else
		{
			$this->params->def('page_heading', Text::_('COM_ACTPARSE_DAMAGETYPES'));
		}

		// Set Pagetitle
		if (!$menu)
		{
			$title = Text::_('COM_ACTPARSE_DAMAGETYPES');
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

		$this->subtitle = Text::_('COM_ACTPARSE_DAMAGETYPES_FROM') . ' "' . $crumbs['combatant'] . '"';

		if ($showpath)
		{
			if ($activeMenuView == 'raids')
			{
				$path->addItem($crumbs['raidname'],  JRoute::_('index.php?view=encounters&enc_rid=' . $crumbs['rid']));
			}

			$path->addItem($crumbs['encname'], JRoute::_('index.php?view=combatants&encid=' . $crumbs['encid']));
			$path->addItem($crumbs['combatant']);
		}
		else
		{
			if ($activeMenuView == 'raids')
			{
				$path->addItem(Text::_('COM_ACTPARSE_RAID'),  JRoute::_('index.php?view=encounters&enc_rid=' . $crumbs['rid']));
			}

			$path->addItem(Text::_('COM_ACTPARSE_COMBATANTS'), JRoute::_('index.php?view=combatants&encid=' . $crumbs['encid']));
			$path->addItem(Text::_('COM_ACTPARSE_DAMAGETYPES'));
		}
	}
}
