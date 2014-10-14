<?php
/**
 * @package     ACT Parse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;
?>
<?php
	JToolBarHelper::title( JText::_( 'COM_ACTPARSE_INFO' ), 'systeminfo.png' );
	JToolBarHelper::preferences('com_actparse',550);
?>
<form action="index.php?option=com_actparse&task=info.create_fields" method="post" name="adminform" id="adminform">
<?php echo JText::_( 'COM_ACTPARSE_INFO_PAGETEXT' ); ?>
<form action="index.php?option=com_actparse&task=info.create_fields" method="post" name="adminform" id="adminform">
	<input type="submit" value="<?php echo JText::_('COM_ACTPARSE_CREATE_FIELDS'); ?>">
</form>
<br/>
<form action="index.php?option=com_actparse&task=info.update_fields" method="post" name="adminform" id="adminform">
	<input type="submit" value="<?php echo JText::_('COM_ACTPARSE_UPDATE_FIELDS'); ?>">
</form>
