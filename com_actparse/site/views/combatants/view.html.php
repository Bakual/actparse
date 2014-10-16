<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

/**
 * HTML View class for the actparse Component
 */
class ActparseViewCombatants extends JViewLegacy
{
	function display($tpl = null)
	{
		// Applying CSS file
		JHTML::stylesheet('actparse.css', 'components/com_actparse/css/');

		$state  = $this->get('State');
		$params = $state->get('params');

		$hide_parse = $params->get('hide_parse', 0);

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

		include 'components/com_actparse/graphlib/phpgraphlib.php';

		// Get some data from the models
		$items      = $this->get('Items');
		$pagination = $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$cols = (array) $params->get('combatantcolumns');

		// Name aus dem Array rauslöschen, da sowieso obligatorisch angezeigt
		$key = array_search('name', $cols);

		if ($key !== false)
		{
			unset ($cols[$key]);
		}

		// build list show NPC
		$show_npc   = $state->get('show_npc');
		$javascript = 'onchange="document.adminForm.submit();"';
		$npclist[]  = JHTML::_('select.option', '0', 'PC & NPC');
		$npclist[]  = JHTML::_('select.option', 'T', 'PC');
		$npclist[]  = JHTML::_('select.option', 'F', 'NPC');
		$this->npc  = JHTML::_('select.genericlist', $npclist, 'show_npc', 'class="inputbox" size="1" style="width:8em;"' . $javascript, 'value', 'text', $show_npc);

		// Daten für Graph vorbereiten (in Array umfüllen)
		$showgraph = $params->get('show_graph');

		if ($showgraph)
		{
			$graphitems    = null;
			$graphsettings = null;
			$order         = $state->get('list.ordering');

			foreach ($items as $row)
			{
				$combatant              = $row->name;
				$type                   = $row->$order;
				$graphitems[$combatant] = $type;
			}

			if (!count($graphitems) || (!array_sum($graphitems)))
			{
				$showgraph = '0';
			}

			if ($order == 'starttime' || $order == 'endtime') $showgraph = '0';
			if ($order == 'healed' || $order == 'exthps') $graphsettings['Heal'] = '1';

			// Daten in Session speichern für Graph
			$session = JFactory::getSession();
			$session->set('GraphItems', $graphitems);
			$session->set('GraphSettings', $graphsettings);
		}

		// push data into the template
		$this->state      = $state;
		$this->items      = $items;
		$this->cols       = $cols;
		$this->pagination = $pagination;
		$this->params     = $params;
		$this->showgraph  = $showgraph;

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
			$this->params->def('page_heading', JText::_('COM_ACTPARSE_COMBATANTS'));
		}

		// Set Pagetitle
		if (!$menu)
		{
			$title = JText::_('COM_ACTPARSE_COMBATANTS');
		}
		else
		{
			$title = $this->params->get('page_title', '');
		}

		if ($app->get('sitename_pagetitles', 0))
		{
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
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

		$this->subtitle = JText::_('COM_ACTPARSE_COMBATANTS_FROM') . ' "' . $crumbs['encname'] . '"';

		if ($showpath)
		{
			if ($activeMenuView == 'raids')
			{
				$path->addItem($crumbs['raidname'],  JRoute::_('index.php?view=encounters&enc_rid=' . $crumbs['rid']));
			}

			$path->addItem($crumbs['encname']);
		}
		else
		{
			if ($activeMenuView == 'raids')
			{
				$path->addItem(JText::_('COM_ACTPARSE_RAID'),  JRoute::_('index.php?view=encounters&enc_rid=' . $crumbs['rid']));
			}

			$path->addItem(JText::_('COM_ACTPARSE_COMBATANTS'));
		}
	}
}
