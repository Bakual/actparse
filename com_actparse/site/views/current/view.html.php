<?php
/* Thomas Hunziker - www.bakual.ch - Januar 2010 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
/**
 * HTML View class for the actparse Component
 */
class ActparseViewCurrent extends JView
{
	function display($tpl = null)
	{
		// Applying CSS file
		JHTML::stylesheet('actparse.css', 'components/com_actparse/css/');

		$app	= JFactory::getApplication();
		$state	= $this->get('State');
		$params	= $state->get('params');

		$hide_parse			= $params->get('hide_parse', 0);
		// Check if User is logged in if that parameter is set in Backend
		if ($hide_parse) {
			$user	= & JFactory::getUser();
			if ($user->guest == 1) {
				$uri	= JFactory::getURI();
				$return	= $uri->toString();
				$url	= 'index.php?option=com_user&view=login';
				$url	.= '&return='.base64_encode($return);
				$app->redirect($url, JError::raiseWarning( 403, JText::_('You must login first') ) );
			}
		}

		include ('components'.DS.'com_actparse'.DS.'graphlib'.DS.'phpgraphlib.php');

		// Get some data from the models
		$items		= $this->get('Items');
		$pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$cols		= (array) $params->get('currentcolumns');
		// Name aus dem Array rauslöschen, da sowieso obligatorisch angezeigt
		$key	= array_search('name', $cols);
		if ($key !== FALSE) {
			unset ($cols[$key]);
		}

		// Set Auto-Refresh Time if set
		if ($params->get('refresh')) {
			$this->document->setMetaData('refresh', $params->get('refreshtime'), true);
		}

		// build list show NPC
		$show_npc	= $state->get('show_npc');
		$javascript	= 'onchange="document.adminForm.submit();"';
		$npclist[]	= JHTML::_('select.option',  '0', 'PC & NPC');
		$npclist[]	= JHTML::_('select.option',  'T', 'PC');
		$npclist[]	= JHTML::_('select.option',  'F', 'NPC');
		$this->npc	= JHTML::_('select.genericlist',   $npclist, 'show_npc', 'class="inputbox" size="1" style="width:8em;"'.$javascript, 'value', 'text', $show_npc);

		// Daten für Graph vorbereiten (in Array umfüllen)
		$showgraph		= $params->get('show_graph');
		if ($showgraph){
			$graphitems		= NULL;
			$graphsettings	= NULL;
			$order = $state->get('list.ordering');
			foreach ($items as $row) {
				$combatant				= $row->name;
				$type					= $row->$order;
				$graphitems[$combatant]	= $type;
			}
			if (!count($graphitems) || (!array_sum($graphitems))){
				$showgraph = '0';
			}
			if ($order == 'starttime' || $order == 'endtime') $showgraph = '0';
			if ($order == 'healed' || $order == 'exthps') $graphsettings['Heal'] = '1';
		}

		// Daten in Session speichern für Graph
		$session	=& JFactory::getSession();
		$session->set('GraphItems',$graphitems);
		$session->set('GraphSettings',$graphsettings);

		// push data into the template
		$this->assignRef('state',		$state);
		$this->assignRef('items',		$items);
		$this->assignRef('cols',		$cols);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('params',		$params);
		$this->assignRef('showgraph',	$showgraph);

		$this->_prepareDocument();
		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app	= JFactory::getApplication();

		// Set Page Header if not already set in the menu entry
		$menus	= $app->getMenu();
		$menu 	= $menus->getActive();
		if ($menu){
			$this->params->def('page_heading', $menu->title);
		} else {
			$this->params->def('page_heading', JText::_('COM_ACTPARSE_COMBATANTS'));
		}

		// Set Pagetitle
		if (!$menu) {
			$title = JText::_('COM_ACTPARSE_COMBATANTS');
		} else {
			$title = $this->params->get('page_title', '');
		}
		if ($app->getCfg('sitename_pagetitles', 0)) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		$this->document->setTitle($title);

		// Set MetaData from menu entry if available
		if ($this->params->get('menu-meta_description')){
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}
		if ($this->params->get('menu-meta_keywords')){
			$this->document->setMetaData('keywords', $this->params->get('menu-meta_keywords'));
		}

		// Set Enviroment Variables (Breadcrumbs)
		$this->subtitle	= JTEXT::_('COM_ACTPARSE_COMBATANTS_CURRENT_ENCOUNTER');
	}
}