<?php
/**
 * @package         ACTParse
 * @subpackage      Component.Site
 * @author          Thomas Hunziker <admin@bakual.net>
 * @copyright   © 2022 - Thomas Hunziker
 * @license         http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$limit     = (int) $this->params->get('limit', '');
$user      = Factory::getUser();
$markuser  = $this->params->get('mark_user');
?>
<div class="com-actparse-combatants<?php echo $this->pageclass_sfx; ?> category-list">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	<?php endif; ?>
	<h2><span class="subheading-category"><?php echo $this->subtitle; ?></span></h2>
	<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post" id="adminForm"
		  name="adminForm" class="com-actparse-combatants__combatants">
		<div class="com-actparse__filter btn-group">
			<label class="filter-show_npc-lbl visually-hidden" for="show_npc">
				<?php echo Text::_('JGLOBAL_FILTER_LABEL'); ?>
			</label>
			<?php echo $this->npc; ?>
		</div>
		<?php if ($this->params->get('show_pagination_limit')) : ?>
			<div class="com-actparse-combatants__pagination btn-group float-end">
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
				<?php echo Text::sprintf('COM_ACTPARSE_NO_ENTRIES', Text::_('COM_ACTPARSE_COMBATANTS')); ?>
			</div>
		<?php else : ?>
			<table class="com-actparse-combatants__table category table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th><?php echo HtmlHelper::_('grid.sort', 'COM_ACTPARSE_NAME', 'name', $listDirn, $listOrder); ?></th>
					<?php foreach ($this->cols as $col) : ?>
						<th><?php echo HtmlHelper::_('grid.sort', 'COM_ACTPARSE_' . $col, $col, $listDirn, $listOrder); ?></th>
					<?php endforeach; ?>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($this->items as $i => $item) : ?>
					<?php $class = ($markuser && (strtolower($user->username) == strtolower($item->name))) ? ' info' : ''; ?>
					<tr class="cat-list-row<?php echo ($i % 2) . $class; ?>">
						<td><a href="<?php echo Route::_('index.php?view=damagetypes&encid=' . $item->encid . '&combatant=' . $item->name); ?>"><?php echo $item->name; ?></a>
						</td>
						<?php foreach ($this->cols as $col) : ?>
							<td>
								<?php if ($col == 'starttime' or $col == 'endtime') : ?>
									<?php if ($item->$col !== '0000-00-00 00:00:00') : ?>
										<?php echo HtmlHelper::_('date', $item->$col, Text::_('DATE_FORMAT_LC6'), 'UTC'); ?>
									<?php else : ?>
										---
									<?php endif; ?>
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
		<?php echo LayoutHelper::render('blocks.pagination', array('view' => 'combatants', 'pagination' => $this->pagination, 'params' => $this->params)); ?>
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	</form>
	<br/>
	<div id="actChart-container">
		<canvas id="actChart"></canvas>
	</div>
</div>
