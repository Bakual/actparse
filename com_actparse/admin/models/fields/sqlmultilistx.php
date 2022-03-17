<?php
/**
 * @copyright      Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;

class JFormFieldSQLMultiListX extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since    1.6
	 */
	protected $type = 'SQLMultiListX';

	/**
	 * Method to get the field options.
	 *
	 * @return    array    The field option objects.
	 * @since    1.6
	 */
	public function getOptions()
	{
		// Initialize variables.
		$options = array();

		$db = Factory::getDbo();

		$query = $this->element['sql'];

		// Get the options.
		$db->setQuery($query);

		$columns = $db->loadObjectList();
		foreach ($columns as $column)
		{
			$options[$column->Field] = $column->Field;
		}

		// Check for a database error.
		if ($db->getErrorNum())
		{
			Factory::getApplication()->enqueueMessage($db->getErrorMsg(), 'error');
		}

		return $options;
	}
}
