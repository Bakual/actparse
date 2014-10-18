<?php
/**
 * @package     ACTParse
 * @subpackage  Module
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

$itemid = (int) $params->get('menuitem');
?>
<ul class="raidlist">
	<?php foreach ($rows as $row) : ?>
		<li><a href="<?php echo JRoute::_('index.php?option=com_actparse&view=encounters&enc_rid=' . $row->id . '&Itemid=' . $itemid); ?>">
			<?php echo $row->raidname; ?> (<?php echo JHtml::_('date', $row->date, JText::_('DATE_FORMAT_LC4'), 'UTC'); ?>)</a>
		</li>
	<?php endforeach; ?>
</ul>
