<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
/**
 * HTML View class for the actparse Component
 */
class ActparseViewSearchbydate extends JViewLegacy
{
	function display($tpl = null)
	{
		// Applying CSS file
		JHTML::stylesheet('actparse.css', 'components/com_actparse/css/');

		$state		= $this->get('State');
		$params		= $state->get('params');

		$hide_parse	= $params->get('hide_parse', 0);

		// Check if User is logged in if that parameter is set in Backend
		if ($hide_parse) {
			$user	= & JFactory::getUser();
			if ($user->guest == 1) {
				$app	= JFactory::getApplication();
				$uri	= JFactory::getURI();
				$return	= $uri->toString();
				$url	= 'index.php?option=com_user&view=login';
				$url	.= '&return='.base64_encode($return);
				$app->redirect($url, JError::raiseWarning( 403, JText::_('You must login first') ) );
			}
		}

		// Get data from the models, only if a filter is set
		$items	= $state->get('show_result') ? $this->get('Items') : array();

		$cols		= (array) $params->get('searchcolumns');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Title aus dem Array rausl�schen, da sowieso obligatorisch angezeigt
		$key	= array_search('title', $cols);
		if ($key !== FALSE) {
			unset ($cols[$key]);
		}

		// build Zones list
		$zones	= $this->get('ZoneList');
		$javascript	= 'onchange="document.adminForm.submit();"';
		$z_option[]	= JHTML::_('select.option',  '', '');
		$z_option[]	= JHTML::_('select.option',  '%', JText::_('COM_ACTPARSE_ALL_ZONES'));
		$zones = array_merge($z_option, $zones);
		$this->zonelist	= JHTML::_('select.genericlist', $zones, 'zone', 'class="inputbox" size="1"'.$javascript, 'value', 'text', $state->get('zone'));

		// build Titles list
		$titles	= $this->get('TitleList');
		$javascript	= 'onchange="document.adminForm.submit();"';
		$t_option[]	= JHTML::_('select.option',  '', '');
		$titles = array_merge($t_option, $titles);
		$this->titlelist	= JHTML::_('select.genericlist', $titles, 'title', 'class="inputbox" size="1"'.$javascript, 'value', 'text', $state->get('title'));

		// Daten f�r Graph vorbereiten (in Array umf�llen)
		$showgraph		= $params->get('show_graph');
		if ($showgraph){
			$graphitems		= NULL;
			$graphsettings	= NULL;
			$order = $state->get('list.ordering');
			foreach ($items as $row) {
				$combatant				= '('.$row->encid.') '.$row->title;
				$type					= $row->$order;
				$graphitems[$combatant]	= $type;
			}
			if (!count($graphitems) || (!array_sum($graphitems))){
				$showgraph = '0';
			}
			if ($order == 'starttime' || $order == 'endtime') $showgraph = '0';
			if ($order == 'healed' || $order == 'exthps') $graphsettings['Heal'] = '1';

			// Daten in Session speichern f�r Graph
			$session	=& JFactory::getSession();
			$session->set('GraphItems',$graphitems);
			$session->set('GraphSettings',$graphsettings);

		}

		// push data into the template
		$this->assignRef('state',		$state);
		$this->assignRef('items',		$items);
		$this->assignRef('cols',		$cols);
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
			$this->params->def('page_heading', JText::_('COM_ACTPARSE_EXT_SEARCH'));
		}

		// Set Pagetitle
		if (!$menu) {
			$title = JText::_('COM_ACTPARSE_EXT_SEARCH');
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
	}
}
