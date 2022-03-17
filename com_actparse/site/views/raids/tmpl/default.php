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

?>
<div class="category-list<?php echo $this->pageclass_sfx; ?> actparse-container<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	<?php endif; ?>
	<ul class="nav nav-tabs nav-stacked">
	<?php foreach ($this->items as $row) : ?>
		<li><a href="<?php echo JRoute::_('index.php?view=encounters&enc_rid='.$row->id); ?>" ><?php echo $row->raidname; ?> (<?php echo HtmlHelper::_('date', $row->date, Text::_('DATE_FORMAT_LC4'), 'UTC'); ?>)</a></li>
	<?php endforeach; ?>
	</ul>
</div>