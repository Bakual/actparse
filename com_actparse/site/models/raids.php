<?php
/* Thomas Hunziker - www.bakual.ch - Januar 2010 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 *ACT Parser Component Raids Model
 *
 */
class ActparseModelRaids extends JModel
{
	function _buildQuery()
	{
		// TODO: Cache on the fingerprint of the arguments
		$db			= JFactory::getDBO();

		$select = 'rt.*';
		$from	= '#__actparse_raids AS rt';
		$orderby = 'raidname ASC';
		
		$query = "SELECT " . $select .
				"\n FROM " . $from .
				"\n WHERE rt.published = 1" .
				"\n ORDER BY rt." . $orderby;

		return $query;
	}
	function getData( $options=array() )
	{
		$query	= $this->_buildQuery( $options );
		$this->_data = $this->_getList( $query );

		return $this->_data;
	}
}