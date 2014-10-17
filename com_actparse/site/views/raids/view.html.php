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
class ActparseViewRaids extends JViewLegacy
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();

		// Parameter auslesen
		$params		= $app->getParams();
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
		$items = $this->get('Data');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->assignRef('items',	$items);
		$this->assignRef('params',	$params);

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
			$this->params->def('page_heading', JText::_('COM_ACTPARSE_RAIDS'));
		}

		// Set Pagetitle
		if (!$menu) {
			$title = JText::_('COM_ACTPARSE_RAIDS');
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
