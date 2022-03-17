<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Utilities\ArrayHelper;

$cid = Factory::getApplication()->input->get( 'cid', array(0), '', 'array' );
ArrayHelper::toInteger($cid, array(0));
OutputFilter::objectHTMLSafe( $this->items, ENT_QUOTES );
?>
<form action="<?php echo Route::_('index.php?option=com_actparse'); ?>" method="post" name="adminForm" id="adminForm">
<div align="center">
	<strong><?php echo Text::_('COM_ACTPARSE_MOVE_ENCOUNTERS_TO_CATEGORY').": "; ?></strong>
	<?php $options[] = HtmlHelper::_('select.option', 0, Text::_('JOPTION_SELECT_CATEGORY'));
	$options = array_merge($options, HtmlHelper::_('category.options', 'com_actparse'));
	echo HtmlHelper::_('select.genericlist', $options, 'catid'); ?>
</div>
<table border="0" width="100%" class="adminlist">
	<tr>
		<th align="left" colspan="2"><?php echo Text::_('COM_ACTPARSE_ENCOUNTERS_TO_MOVE'); ?></th>
	</tr>
	<tr>
		<th class="title" width="40%"><?php echo Text::_('JGLOBAL_TITLE'); ?></th>
		<th align="left"><?php echo Text::_('COM_ACTPARSE_OLD_CAT'); ?></th>
	</tr>
	<?php foreach($this->items as $row) { ?>
		<tr>
			<td align="left">
				<?php echo $row->title; ?>
				<input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />
			</td>
			<td align="left"><?php echo $row->category ?></td>
		</tr>
	<?php } ?>
</table>
	<input type="hidden" name="task" value="" />
	<?php echo HtmlHelper::_( 'form.token' ); ?>
</form>
