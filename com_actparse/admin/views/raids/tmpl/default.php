<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtmlFormbehavior::chosen();

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_actparse&view=raids'); ?>" method="post" name="adminForm" id="adminForm">
	<?php if(!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>
			<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
			<?php if (empty($this->items)) : ?>
				<div class="alert alert-no-items">
					<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
				</div>
			<?php else : ?>
			<table class="table table-striped" id="raidList">
				<thead>
					<tr>
						<th width="1%" class="hidden-phone">
							<input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this)" />
						</th>
						<th width="1%" style="min-width:40px" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort',  'JPUBLISHED', 'raids.published', $listDirn, $listOrder); ?>
						</th>
						<th class="title">
							<?php echo JHtml::_('searchtools.sort',  'Raidname', 'raids.raidname', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort',  'JDATE', 'raids.date', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort',  'JGRID_HEADING_ID', 'encounters.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($this->items as $i => $item) : ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center hidden-phone">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<td class="center">
							<?php echo JHtml::_('jgrid.published', $item->published, $i, 'raids.', true);?>
						</td>
						<td>
							<a href="<?php echo JRoute::_('index.php?option=com_actparse&task=raid.edit&id=' . (int) $item->id); ?>">
								<?php echo $this->escape($item->raidname); ?></a>
						</td>
						<td class="center">
							<?php echo JHTML::Date($item->date, JText::_('DATE_FORMAT_LC4'), 'UTC'); ?>
						</td>
						<td class="center hidden-phone">
							<?php echo (int) $item->id; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
		<?php echo $this->pagination->getListFooter(); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
