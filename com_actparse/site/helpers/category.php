<?php
/**
 * @package     Actparse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

/**
 * Actparse Component Category Tree
 *
 * @since  2.0
 */
class ActparseCategories extends JCategories
{
	/**
	 * Constructor
	 *
	 * @param   array  $options  Obtions
	 */
	public function __construct($options = array())
	{
		if (!isset($options['table']))
		{
			$options['table'] = 'encounter_table';
		}

		$options['extension'] = 'com_actparse';

		parent::__construct($options);
	}
}
