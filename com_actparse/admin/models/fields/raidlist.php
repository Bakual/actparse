<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldRaidlist extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var   string
	 * @since 1.0
	 */
	protected $type = 'Raidlist';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 * @since   1.0
	 */
	public function getOptions()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('id AS value, raidname, date');
		$query->from('#__actparse_raids');
		$query->order('raidname ASC');
		$db->setQuery($query);

		$options = $db->loadObjectList();

		foreach ($options as $option)
		{
			$option->text = $option->raidname . ' (' . JHTML::Date($option->date, JText::_('DATE_FORMAT_LC4'), 'UTC') . ')';
		}

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
