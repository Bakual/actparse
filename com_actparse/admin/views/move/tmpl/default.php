<?php
/**
 * @package         ACTParse
 * @subpackage      Component.Administrator
 * @author          Thomas Hunziker <admin@bakual.net>
 * @copyright   © 2023 - Thomas Hunziker
 * @license         http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\Utilities\ArrayHelper;

$cid = Factory::getApplication()->input->get('cid', array(0), '', 'array');
ArrayHelper::toInteger($cid, array(0));
OutputFilter::objectHTMLSafe($this->items, ENT_QUOTES);
?>
<form action="<?php echo JRoute::_('index.php?option=com_actparse'); ?>" method="post" name="adminForm" id="adminForm">
	<div align="center">
		<strong><?php echo Text::_('COM_ACTPARSE_MOVE_ENCOUNTERS_TO_RAID') . ": "; ?></strong>
		<?php $options[] = HTMLHelper::_('select.option', 0, Text::_('COM_ACTPARSE_SELECT_RAID'));
		$options         = array_merge($options, $this->raids);
		echo HTMLHelper::_('select.genericlist', $options, 'rid'); ?>
	</div>
	<table border="0" width="100%" class="adminlist">
		<tr>
			<th colspan="2">
			<?php echo Text::_('COM_ACTPARSE_ENCOUNTERS_TO_MOVE'); ?></th>
		</tr>
		<tr>
			<th class="title" width="40%"><?php echo Text::_('JGLOBAL_TITLE'); ?></th>
			<th><?php echo Text::_('COM_ACTPARSE_OLD_RAID'); ?></th>
		</tr>
		<?php
		foreach ($this->items as $row)
		{ ?>
			<tr>
				<td>
					<?php echo $row->title; ?>
					<input type="hidden" name="cid[]" value="<?php echo $row->id; ?>"/>
				</td>
				<td>
					<?php if ($row->raidname):
						echo $row->raidname . ' (' . HTMLHelper::Date($row->date, Text::_('DATE_FORMAT_LC4'), 'UTC') . ')';
					endif; ?>
				</td>
			</tr>
		<?php } ?>
	</table>
	<input type="hidden" name="task" value=""/>
	<?php echo HTMLHelper::_('form.token'); ?>
</form>
