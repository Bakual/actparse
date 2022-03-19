<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

HtmlHelper::_('bootstrap.tooltip');
HtmlHelper::_('bootstrap.modal');

$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$limit     = (int) $this->params->get('limit', '');
?>
<div class="category-list<?php echo $this->pageclass_sfx; ?> actparse-container<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	<?php endif; ?>
	<h2><span class="subheading-category"><?php echo $this->subtitle; ?></span></h2>
	<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post" id="adminForm" name="adminForm"  class="com-actparse-encounters__encounters">
		<div class="com-actparse__filter btn-group">
			<?php if ($this->params->get('filter_field')) :?>
				<label class="filter-search-lbl visually-hidden" for="filter-search">
					<?php echo Text::_('JGLOBAL_FILTER_LABEL'); ?>
				</label>
				<input type="text" name="filter[search]" id="filter-search"
					   value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="inputbox"
					   onchange="document.adminForm.submit();">
			<?php endif; ?>
			<label class="filter-show_npc-lbl visually-hidden" for="show_npc">
				<?php echo Text::_('JGLOBAL_FILTER_LABEL'); ?>
			</label>
			<?php echo $this->npc; ?>
		</div>
		<?php if ($this->params->get('show_pagination_limit')) : ?>
			<div class="com-actparse-encounters__pagination btn-group float-end">
				<label for="limit" class="visually-hidden">
					<?php echo Text::_('JGLOBAL_DISPLAY_NUM'); ?>
				</label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		<?php endif;
		if ($this->params->get('filter_field')) : ?>
			</fieldset>
		<?php endif; ?>
		<?php if (!count($this->items)) : ?>
			<div class="no_entries alert alert-error"><?php echo Text::sprintf('COM_ACTPARSE_NO_ENTRIES', Text::_('COM_ACTPARSE_ENCOUNTERS')); ?></div>
		<?php else : ?>
			<table class="table table-striped table-hover table-condensed">
			<!-- Create the headers with sorting links -->
				<thead><tr>
					<th><?php echo HtmlHelper::_('grid.sort', 'JGLOBAL_TITLE', 'title', $listDirn, $listOrder); ?></th>
					<?php foreach ($this->cols as $col) { ?>
						<th><?php echo HtmlHelper::_('grid.sort', 'COM_ACTPARSE_'.$col, $col, $listDirn, $listOrder); ?></th>
					<?php } ?>
				</tr></thead>
			<!-- Begin Data -->
				<tbody>
					<?php foreach($this->items as $i => $item) : ?>
						<tr class="cat-list-row<?php echo $i % 2; echo isset($item->all) ? ' success' : ''; ?>">
							<td><a href="<?php echo JRoute::_('index.php?view=combatants&encid='.$item->encid); ?>" ><?php echo $item->title; ?></a></td>
							<?php foreach ($this->cols as $col) : ?>
								<td>
									<?php if ($col == 'starttime' OR $col == 'endtime') :
										echo  HtmlHelper::_('date', $item->$col, 'Y-m-d H:m:s', 'UTC');
									elseif($col == 'zone') :
										echo $item->$col;
									else :
										echo number_format((int) $item->$col, 0, Text::_('DECIMALS_SEPARATOR'), Text::_('THOUSANDS_SEPARATOR'));
									endif; ?>
								</td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
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
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<input type="hidden" name="limitstart" value=""/>

	</form>
</div>
