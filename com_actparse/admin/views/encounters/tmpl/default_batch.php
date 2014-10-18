<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

$published = $this->state->get('filter.state');
?>
<div class="modal hide fade" id="collapseModal">
	<div class="modal-header">
		<button type="button" role="presentation" class="close" data-dismiss="modal">x</button>
		<h3><?php echo JText::_('COM_ACTPARSE_BATCH_OPTIONS');?></h3>
	</div>
	<div class="modal-body">
		<p><?php echo JText::_('COM_ACTPARSE_BATCH_TIP'); ?></p>
		<div class="control-group">
			<div class="controls">
				<label id="batch-raid-lbl" for="batch-raid-id" class="hasTooltip" title="<?php echo JText::_('COM_ACTPARSE_BATCH_RAID_LABEL'); ?>::<?php echo JText::_('COM_ACTPARSE_BATCH_RAID_LABEL_DESC'); ?>">
					<?php echo JText::_('COM_ACTPARSE_BATCH_RAID_LABEL'); ?>
				</label>
				<select name="batch[raid_id]" class="inputbox" id="batch-raid-id">
					<option value=""><?php echo JText::_('COM_ACTPARSE_BATCH_RAID_NOCHANGE'); ?></option>
					<?php echo JHtml::_('select.options', $this->raids, 'value', 'text'); ?>
				</select>
			</div>
		</div>
		<?php if ($published >= 0) : ?>
		<div class="control-group">
			<div class="controls">
				<?php echo JHtml::_('batch.item', 'com_actparse');?>
			</div>
		</div>
		<?php endif; ?>
	</div>
	<div class="modal-footer">
		<button class="btn" type="button" onclick="document.id('batch-category-id').value='';" data-dismiss="modal">
			<?php echo JText::_('JCANCEL'); ?>
		</button>
		<button class="btn btn-primary" type="submit" onclick="Joomla.submitbutton('encounter.batch');">
			<?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
		</button>
	</div>
</div>