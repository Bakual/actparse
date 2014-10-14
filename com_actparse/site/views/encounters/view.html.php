<?php
/**
 * @package     ACT Parse
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
class ActparseViewEncounters extends JViewLegacy
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

		// Get some data from the models
		$items		= $this->get('Items');
		$pagination	= $this->get('Pagination');

		$cols		= (array) $params->get('enccolumns');
		// Get additional stuff if single raid
		if ($state->get('raid.id')){
			// Populate ALL Encounter if set
			if ($params->get('show_all')) {
				$item	= $this->get('All');
				if ($item){
					$item->title 	= JText::_($params->get('name_all', 'COM_ACTPARSE_SAVE_FIRST'));
					$item->all		= true;
					array_unshift($items, $item);
				}
			}
		}
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

		// push data into the template
		$this->assignRef('state',		$state);
		$this->assignRef('items',		$items);
		$this->assignRef('cols',		$cols);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('params',		$params);

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
			$this->params->def('page_heading', JText::_('COM_ACTPARSE_ALL_ENCOUNTERS'));
		}

		// Set Pagetitle
		if (!$menu) {
			$title = JText::_('COM_ACTPARSE_ALL_ENCOUNTERS');
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
		$showpath		= $this->params->get('show_ext_path');
		$crumbs			= $this->get('Crumbs');
		$activeMenuView	= $menu ? $menu->query['view'] : '';
		$path			= $app->getPathway();

		if ($this->state->get('raid.id')){
			$this->subtitle	= JText::_('COM_ACTPARSE_ENCOUNTERS_FROM_RAID').' "'.$crumbs['raidname'].' ('.JHtml::Date($crumbs['date'], JText::_('DATE_FORMAT_LC4'), 'UTC') . ')"';
			if ($showpath) {
				if ($activeMenuView == 'raids') {
					$path->addItem($crumbs['raidname']);
				}
			} else {
				if ($activeMenuView == 'raids') {
					$path->addItem(JText::_('COM_ACTPARSE_RAID'));
				}
			}
		} else {
			$this->subtitle	= JText::_('COM_ACTPARSE_ALL_ENCOUNTERS');
		}
	}
}