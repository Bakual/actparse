<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die('Restricted access');
?>
<div class="actparse-container<?php echo htmlspecialchars($this->params->get('pageclass_sfx')); ?>">
<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif; ?>
<ul>
<?php
// Auslesen der Datens�tze im Array
foreach ($this->items as $row) {
	?><li><a href="<?php echo JRoute::_('index.php?view=encounters&enc_rid='.$row->id); ?>" ><?php echo $row->raidname; ?> (<?php echo JHtml::_('date', $row->date, JText::_('DATE_FORMAT_LC4'), 'UTC'); ?>)</a></li>
	<?php
}
?>
</ul>
