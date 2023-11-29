<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   © 2023 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('bootstrap.tooltip');

$user      = Factory::getUser();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo Route::_('index.php?option=com_actparse&view=encounters'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
		<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped" id="encounterList">
				<thead>
					<tr>
						<th width="1%" class="hidden-phone">
							<input type="checkbox" name="checkall-toggle" value="" title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
						</th>
						<th width="1%" style="min-width:40px" class="nowrap center">
							<?php echo HTMLHelper::_('searchtools.sort', 'JPUBLISHED', 'encounters.published', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_TITLE', 'encounters.title', $listDirn, $listOrder); ?>
						</th>
						<th width="20%">
							<?php echo HTMLHelper::_('searchtools.sort',  'Raid', 'raidname', $listDirn, $listOrder); ?>
						</th>
						<th width="5%" class="nowrap hidden-phone">
							<?php echo HTMLHelper::_('searchtools.sort',  'Start', 'encounters.starttime', $listDirn, $listOrder); ?>
						</th>
						<th width="5%" class="nowrap hidden-phone">
							<?php echo HTMLHelper::_('searchtools.sort',  'Zone', 'encounters.zone', $listDirn, $listOrder); ?>
						</th>
						<th width="5%" class="nowrap hidden-phone">
							<?php echo HTMLHelper::_('searchtools.sort',  'EncounterID', 'encounters.encid', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap hidden-phone">
							<?php echo HTMLHelper::_('searchtools.sort',  'JGRID_HEADING_ID', 'encounters.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($this->items as $i => $item) : ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center hidden-phone">
							<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
						</td>
						<td class="center">
							<?php echo HTMLHelper::_('jgrid.published', $item->published, $i, 'encounters.', true); ?>
						</td>
						<td>
							<a href="<?php echo JRoute::_('index.php?option=com_actparse&task=encounter.edit&id=' . (int) $item->id); ?>">
								<?php echo $this->escape($item->title); ?></a>
							<div class="small">
								<?php echo Text::_('JCATEGORY') . ': ' . $this->escape($item->category_title); ?>
							</div>
						</td>
						<td class="center small">
							<?php if ($item->raidname) : ?>
								<?php echo $item->raidname . ' (' . HTMLHelper::Date($item->date, Text::_('DATE_FORMAT_LC4'), 'UTC') . ')'; ?>
							<?php endif; ?>
						</td>
						<td class="center hidden-phone">
							<?php echo HTMLHelper::Date($item->starttime, Text::_('DATE_FORMAT_LC4'), 'UTC'); ?>
						</td>
						<td class="center hidden-phone">
							<?php echo $this->escape($item->zone); ?>
						</td>
						<td class="center hidden-phone">
							<?php echo $this->escape($item->encid); ?>
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

		<?php //Load the batch processing form. ?>
		<?php if ($user->authorise('core.create', 'com_actparse')
			&& $user->authorise('core.edit', 'com_actparse')
			&& $user->authorise('core.edit.state', 'com_actparse')) : ?>
			<?php echo HTMLHelper::_(
				'bootstrap.renderModal',
				'collapseModal',
				array(
					'title'  => Text::_('COM_ACTPARSE_BATCH_OPTIONS'),
					'footer' => $this->loadTemplate('batch_footer'),
				),
				$this->loadTemplate('batch_body')
			); ?>
		<?php endif; ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
</form>
