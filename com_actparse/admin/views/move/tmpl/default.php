<?php
/* Thomas Hunziker - www.bakual.net - Januar 2010 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
?>

<?php
	$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
	JArrayHelper::toInteger($cid, array(0));
	JFilterOutput::objectHTMLSafe( $this->items, ENT_QUOTES );
?>

<form action="<?php echo JRoute::_('index.php?option=com_actparse'); ?>" method="post" name="adminForm" id="adminForm">
<div align="center">
	<strong><?php echo JText::_('COM_ACTPARSE_MOVE_ENCOUNTERS_TO_RAID').": "; ?></strong>
	<?php $options[] = JHtml::_('select.option', 0, JText::_('COM_ACTPARSE_SELECT_RAID'));
	$options = array_merge($options, $this->raids);
	echo JHTML::_('select.genericlist', $options, 'rid'); ?> 
</div>
<table border="0" width="100%" class="adminlist">
	<tr>
		<th align="left" colspan="2"><?php echo JText::_('COM_ACTPARSE_ENCOUNTERS_TO_MOVE'); ?></td>
	</tr>
	<tr>
		<th class="title" width="40%"><?php echo JText::_('JGLOBAL_TITLE'); ?></th>
		<th align="left"><?php echo JText::_('COM_ACTPARSE_OLD_RAID'); ?></th>
	</tr>
	<?php
	foreach($this->items as $row) { ?>
		<tr>
			<td align="left">
				<?php echo $row->title; ?>
				<input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />
			</td>
			<td align="left">
				<?php if ($row->raidname):
					echo $row->raidname.' ('.JHtml::Date($row->date, JText::_('DATE_FORMAT_LC4'), 'UTC'). ')';
				endif; ?>
			</td>
		</tr>
	<?php } ?>
</table>
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
