<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   © 2022 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Uri\Uri;

$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$limit     = (int) $this->params->get('limit', '');
?>
<div class="com-actparse-encounters<?php echo $this->pageclass_sfx; ?> category-list">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	<?php endif; ?>
	<h2><span class="subheading-category"><?php echo $this->subtitle; ?></span></h2>
	<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post" id="adminForm"
		  name="adminForm" class="com-actparse-encounters__encounters">
		<div class="com-actparse__filter btn-group">
			<?php if ($this->params->get('filter_field')) : ?>
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
		<?php endif; ?>
		<?php if (!count($this->items)) : ?>
			<div class="alert alert-info">
				<span class="icon-info-circle" aria-hidden="true"></span><span
						class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
				<?php echo Text::sprintf('COM_ACTPARSE_NO_ENTRIES', Text::_('COM_ACTPARSE_ENCOUNTERS')); ?>
			</div>
		<?php else : ?>
			<table class="com-actparse-encounters__table category table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th><?php echo HtmlHelper::_('grid.sort', 'JGLOBAL_TITLE', 'title', $listDirn, $listOrder); ?></th>
					<?php foreach ($this->cols as $col) : ?>
						<th><?php echo HtmlHelper::_('grid.sort', 'COM_ACTPARSE_' . $col, $col, $listDirn, $listOrder); ?></th>
					<?php endforeach; ?>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($this->items as $i => $item) : ?>
					<tr class="cat-list-row<?php echo $i % 2;
					echo isset($item->all) ? ' success' : ''; ?>">
						<td>
							<a href="<?php echo JRoute::_('index.php?view=combatants&encid=' . $item->encid); ?>"><?php echo $item->title; ?></a>
						</td>
						<?php foreach ($this->cols as $col) : ?>
							<td>
								<?php if ($col == 'starttime' or $col == 'endtime') : ?>
									<?php if ($item->$col !== '0000-00-00 00:00:00') : ?>
										<?php echo HtmlHelper::_('date', $item->$col, Text::_('DATE_FORMAT_LC6'), 'UTC'); ?>
									<?php else : ?>
										---
									<?php endif; ?>
								<?php elseif ($col == 'zone') : ?>
									<?php echo $item->$col; ?>
								<?php else : ?>
									<?php echo is_numeric($item->$col) ? number_format((int) $item->$col, 0, Text::_('DECIMALS_SEPARATOR'), Text::_('THOUSANDS_SEPARATOR')) : $item->$col; ?>
								<?php endif; ?>
							</td>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
		<?php echo LayoutHelper::render('blocks.pagination', array('view' => 'encounters', 'pagination' => $this->pagination, 'params' => $this->params)); ?>
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	</form>
</div>
