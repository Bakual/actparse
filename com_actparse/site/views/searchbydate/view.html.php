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
class ActparseViewSearchbydate extends JViewLegacy
{
	function display($tpl = null)
	{
		$this->state  = $this->get('State');
		$this->params = $this->state->get('params');

		$hide_parse = $this->params->get('hide_parse', 0);

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

		// Get data from the models, only if a filter is set
		$this->items = $this->state->get('show_result') ? $this->get('Items') : array();

		$this->cols = (array) $this->params->get('searchcolumns');

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

		// build Zones list
		$zones      = $this->get('Zonelist');
		$javascript = 'onchange="document.adminForm.submit();"';
		$z_option[] = JHTML::_('select.option',  '', '');
		$z_option[] = JHTML::_('select.option',  '%', JText::_('COM_ACTPARSE_ALL_ZONES'));
		$zones      = array_merge($z_option, $zones);
		$this->zonelist = JHTML::_('select.genericlist', $zones, 'zone', 'class="inputbox" size="1"' . $javascript, 'value', 'text', $this->state->get('zone'));

		// build Titles list
		$titles     = $this->get('Titlelist');
		$javascript = 'onchange="document.adminForm.submit();"';
		$t_option[] = JHTML::_('select.option',  '', '');
		$titles     = array_merge($t_option, $titles);
		$this->titlelist = JHTML::_('select.genericlist', $titles, 'title', 'class="inputbox" size="1"' . $javascript, 'value', 'text', $this->state->get('title'));

		// Daten für Graph vorbereiten (in Array umfüllen)
		$this->showgraph = $this->params->get('show_graph');

		if ($this->showgraph)
		{
			$graphitems    = null;
			$graphsettings = null;
			$order         = $this->state->get('list.ordering');

			foreach ($this->items as $row)
			{
				$combatant              = '(' . $row->encid . ') ' . $row->title;
				$type                   = $row->$order;
				$graphitems[$combatant] = $type;
			}

			if (!count($graphitems) || (!array_sum($graphitems)))
			{
				$this->showgraph = '0';
			}

			if ($order == 'starttime' || $order == 'endtime') $this->showgraph = '0';
			if ($order == 'healed' || $order == 'exthps') $graphsettings['Heal'] = '1';

			// Daten in Session speichern für Graph
			$session = JFactory::getSession();
			$session->set('GraphItems',$graphitems);
			$session->set('GraphSettings',$graphsettings);
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
			$this->params->def('page_heading', JText::_('COM_ACTPARSE_EXT_SEARCH'));
		}

		// Set Pagetitle
		if (!$menu)
		{
			$title = JText::_('COM_ACTPARSE_EXT_SEARCH');
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
	}
}
