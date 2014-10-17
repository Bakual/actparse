<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$limit 		= (int)$this->params->get('limit', '');
$graphlib	= JURI::Root().'components/com_actparse/graphlib/';
?>
<div class="actparse-container<?php echo htmlspecialchars($this->params->get('pageclass_sfx')); ?>">
<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif; ?>
<p><?php echo JText::_('COM_ACTPARSE_LIMITED_TO'); ?></p>
<!-- Suchformular -->
<form action="<?php echo JFilterOutput::ampReplace(JFactory::getURI()->toString()); ?>" method="post" id="adminForm" name="adminForm">
	<table border='0' cellpadding='0' cellspacing='0'>
		<tr>
			<td><?php echo JText::_('COM_ACTPARSE_ZONE'); ?>:&nbsp;</td>
			<td><?php echo $this->zonelist; ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_ACTPARSE_ENCOUNTER'); ?>:&nbsp;</td>
			<td><?php echo $this->titlelist; ?></td>
			<td>&nbsp;<?php echo JText::_('COM_ACTPARSE_BASED_ON_ZONECHOICE'); ?></tr>
		<tr>
			<td><?php echo JText::_('COM_ACTPARSE_STARTDATE'); ?>:&nbsp;</td>
			<td><?php echo JHTML::Calendar($this->state->get('starttime'), 'starttime', 'starttime', '%Y-%m-%d', 'class="inputbox"'); ?></td>
			<td>&nbsp;<?php echo JText::_('COM_ACTPARSE_FORMAT_DATE'); ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_ACTPARSE_ENDDATE'); ?>:&nbsp;</td>
			<td><?php echo JHTML::Calendar($this->state->get('endtime'), 'endtime', 'endtime', '%Y-%m-%d', 'class="inputbox"'); ?></td>
			<td>&nbsp;<?php echo JText::_('COM_ACTPARSE_FORMAT_DATE'); ?></td></tr>
		<tr></tr>
		<tr><td></td><td colspan='2'><input type='submit' value="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"></td><tr>
	</table>
	<br>
	<?php if (!count($this->items)) : ?>
		<div class="no_entries"><?php echo JText::sprintf('COM_ACTPARSE_NO_ENTRIES', JText::_('COM_ACTPARSE_ENCOUNTERS')); ?></div>
	<?php else : ?>
		<table class="category">
		<!-- Create the headers with sorting links -->
			<thead><tr>
				<th><?php echo JHTML::_('grid.sort', 'JGLOBAL_TITLE', 'title', $listDirn, $listOrder); ?></th>
				<?php foreach ($this->cols as $col) { ?>
					<th align="left"><?php echo JHTML::_('grid.sort', 'COM_ACTPARSE_'.$col, $col, $listDirn, $listOrder); ?></th>
				<?php } ?>
			</tr></thead>
		<!-- Begin Data -->
			<tbody>
				<?php foreach($this->items as $i => $item) : ?>
					<tr class="cat-list-row<?php echo $i % 2; echo isset($item->all) ? ' success' : ''; ?>">
						<td align='left'><a href="<?php echo JRoute::_('index.php?view=combatants&encid='.$item->encid); ?>" ><?php echo $item->title; ?></a></td>
						<?php foreach ($this->cols as $col) : ?>
							<td align="left">
								<?php if ($col == 'starttime' OR $col == 'endtime') :
									echo  JHtml::_('date', $item->$col, 'Y-m-d H:m:s', 'UTC');
								elseif($col == 'zone') :
									echo $item->$col;
								else :
									echo (int)$item->$col;
								endif; ?>
							</td>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
</form>
<br />
<?php if ($this->showgraph) { ?>
<!-- GraphLib Aufruf -->
<img src="<?php echo $graphlib ; ?>graph.php"><br>
<!-- GraphLib Werbelink, durch Lizenz benoetigt -->
<a href="http://www.ebrueggeman.com/phpgraphlib"><img src="<?php echo $graphlib ; ?>phpgraphlib_80x15.png" alt="PHPGraphLib - Click For Official Site" width="80" height="15" align="top" style="margin-right:10px;" /></a>
<?php } ?>
</div>
