<?php
/* Thomas Hunziker - www.bakual.net - Januar 2010 */

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$limit 		= (int)$this->params->get('limit', '');
?>
<div class="actparse-container<?php echo htmlspecialchars($this->params->get('pageclass_sfx')); ?>">
<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif; ?>
<h2><span class="subheading-category"><?php echo $this->subtitle; ?></span></h2>
<form action="<?php echo JFilterOutput::ampReplace(JFactory::getURI()->toString()); ?>" method="post" id="adminForm" name="adminForm">
	<?php if ($this->params->get('show_pagination_limit')) : ?>
			<div class="display-limit">
				<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&nbsp;
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
	<?php endif; ?>
	<?php if (!count($this->items)) : ?>
		<div class="no_entries"><?php echo JText::sprintf('COM_ACTPARSE_NO_ENTRIES', JText::_('COM_ACTPARSE_ATTACKTYPES')); ?></div>
	<?php else : ?>
		<div id='Layer1' style='overflow:scroll;'>
		<table class="category">
		<!-- Create the headers with sorting links -->
			<thead><tr>
				<th><?php echo JHTML::_('grid.sort', 'COM_ACTPARSE_TYPE', 'type', $listDirn, $listOrder); ?></th>
				<?php foreach ($this->cols as $col) { ?>
					<th align="left"><?php echo JHTML::_('grid.sort', 'COM_ACTPARSE_'.$col, $col, $listDirn, $listOrder); ?></th>
				<?php } ?>
			</tr></thead>
		<!-- Begin Data -->
			<tbody>
				<?php foreach($this->items as $i => $item) : ?>
					<tr class="cat-list-row<?php echo $i % 2; ?>">
						<td><?php echo $item->type; ?></td>
						<?php foreach ($this->cols as $col) : ?>
							<td align="left">
								<?php if ($col == 'starttime' OR $col == 'endtime') :
									echo  JHtml::_('date', $item->$col, 'Y-m-d H:m:s', 'UTC');
								else :
									echo $item->$col;
								endif; ?>
							</td>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		</div>
	<?php endif;
	if ($this->params->get('show_pagination') && ($this->pagination->get('pages.total') > 1)) : ?>
		<div class="pagination">
			<?php if ($this->params->get('show_pagination_results', 1)) : ?>
				<p class="counter">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</p>
			<?php endif;
			echo $this->pagination->getPagesLinks(); ?>
		</div>
	<?php endif; ?>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
</form>
</div>