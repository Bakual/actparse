<?php
/**
 * @package     ACTParse
 * @subpackage  Module
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   © 2023 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$itemid = (int) $params->get('menuitem');
?>
<ul class="raidlist">
	<?php foreach ($rows as $row) : ?>
		<li><a href="<?php echo JRoute::_('index.php?option=com_actparse&view=encounters&enc_rid=' . $row->id . '&Itemid=' . $itemid); ?>">
			<?php echo $row->raidname; ?> (<?php echo HtmlHelper::_('date', $row->date, Text::_('DATE_FORMAT_LC4'), 'UTC'); ?>)</a>
		</li>
	<?php endforeach; ?>
</ul>
