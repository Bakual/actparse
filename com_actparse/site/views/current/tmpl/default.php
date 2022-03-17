<?php
/**
 * @package         ACTParse
 * @subpackage      Component.Site
 * @author          Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license         http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

HtmlHelper::_('behavior.tooltip');
HtmlHelper::_('behavior.modal');

$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$limit     = (int) $this->params->get('limit', '');
$user      = JFactory::getUser();
$graphlib  = URI::Root() . 'components/com_actparse/graphlib/';
$markuser  = $this->params->get('mark_user');
?>
<div class="category-list<?php echo $this->pageclass_sfx; ?> actparse-container<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	<?php endif; ?>
	<h2><span class="subheading-category"><?php echo $this->subtitle; ?></span></h2>
	<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post" id="adminForm"
		  name="adminForm">
		<fieldset class="filters">
			<legend class="hidelabeltxt">
				<?php echo Text::_('JGLOBAL_FILTER_LABEL'); ?>
			</legend>
			<div class="filter-search">
				<label class="show_npc-lbl" for="show_npc"><?php echo Text::_('JGLOBAL_FILTER_LABEL'); ?></label>
				<?php echo $this->npc; ?>
			</div>
			<?php if ($this->params->get('show_pagination_limit')) : ?>
				<div class="display-limit">
					<?php echo Text::_('JGLOBAL_DISPLAY_NUM'); ?>&nbsp;
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			<?php endif; ?>
		</fieldset>
		<?php if (!count($this->items)) : ?>
			<div class="no_entries alert alert-error"><?php echo Text::sprintf('COM_ACTPARSE_NO_ENTRIES', Text::_('COM_ACTPARSE_COMBATANTS')); ?></div>
		<?php else : ?>
			<table class="table table-striped table-hover table-condensed">
				<!-- Create the headers with sorting links -->
				<thead>
				<tr>
					<th><?php echo HtmlHelper::_('grid.sort', 'COM_ACTPARSE_NAME', 'name', $listDirn, $listOrder); ?></th>
					<?php foreach ($this->cols as $col) { ?>
						<th align="left"><?php echo HtmlHelper::_('grid.sort', 'COM_ACTPARSE_' . $col, $col, $listDirn, $listOrder); ?></th>
					<?php } ?>
				</tr>
				</thead>
				<!-- Begin Data -->
				<tbody>
				<?php foreach ($this->items as $i => $item) :
					$class = ($markuser && (strtolower($user->username) == strtolower($item->name))) ? ' info' : ''; ?>
					<tr class="cat-list-row<?php echo ($i % 2) . $class; ?>">
						<td align='left'><?php echo $item->name; ?></td>
						<?php foreach ($this->cols as $col) : ?>
							<td align="left">
								<?php if ($col == 'starttime' or $col == 'endtime') :
									echo HtmlHelper::_('date', $item->$col, 'Y-m-d H:m:s', 'UTC');
								else :
									echo $item->$col;
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
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	</form>
	<br/>
	<?php if ($this->showgraph) : ?>
		<img src="data:image/png;base64,<?php echo $this->showgraph; ?>" alt="graph">
	<?php endif; ?>
</div>
