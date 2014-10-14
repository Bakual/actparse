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
	$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
	JArrayHelper::toInteger($cid, array(0));
	JFilterOutput::objectHTMLSafe( $this->items, ENT_QUOTES );
?>

<form action="<?php echo JRoute::_('index.php?option=com_actparse'); ?>" method="post" name="adminForm" id="adminForm">
<div align="center">
	<strong><?php echo JText::_('COM_ACTPARSE_MOVE_ENCOUNTERS_TO_CATEGORY').": "; ?></strong>
	<?php $options[] = JHtml::_('select.option', 0, JText::_('JOPTION_SELECT_CATEGORY'));
	$options = array_merge($options, JHtml::_('category.options', 'com_actparse'));
	echo JHTML::_('select.genericlist', $options, 'catid'); ?> 
</div>
<table border="0" width="100%" class="adminlist">
	<tr>
		<th align="left" colspan="2"><?php echo JText::_('COM_ACTPARSE_ENCOUNTERS_TO_MOVE'); ?></td>
	</tr>
	<tr>
		<th class="title" width="40%"><?php echo JText::_('JGLOBAL_TITLE'); ?></th>
		<th align="left"><?php echo JText::_('COM_ACTPARSE_OLD_CAT'); ?></th>
	</tr>
	<?php foreach($this->items as $row) { ?>
		<tr>
			<td align="left">
				<?php echo $row->title; ?>
				<input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />
			</td>
			<td align="left"><?php echo $row->category ?></td>
		</tr>
	<?php } ?>
</table>
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
