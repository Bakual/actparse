<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   © 2022 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

$published = $this->state->get('filter.published');
?>

<div class="container">
	<div class="row">
		<div class="form-group col-md-6">
			<div class="controls">
				<label id="batch-raid-lbl" for="batch-raid-id">
					<?php echo Text::_('COM_ACTPARSE_BATCH_RAID_LABEL'); ?>
				</label>
				<select name="batch[raid_id]" class="custom-select" id="batch-raid-id">
					<option value=""><?php echo Text::_('COM_ACTPARSE_BATCH_RAID_NOCHANGE'); ?></option>
					<?php echo HTMLHelper::_('select.options', $this->raids, 'value', 'text'); ?>
				</select>
			</div>
		</div>
		<?php if ($published >= 0) : ?>
			<div class="row">
				<div class="form-group col-md-6">
					<div class="controls">
						<?php echo LayoutHelper::render('joomla.html.batch.item', array('extension' => 'com_sermonspeaker.sermons')) ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
