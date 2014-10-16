<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   (C) 2014 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtmlFormbehavior::chosen('select');
?>
<script type="text/javascript">
	function Joomla.submitbutton(task)
	{
		if (task == 'encounter.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task);
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_actparse&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="form-horizontal">
		<?php foreach($this->form->getFieldset('general') as $field): ?>
			<?php echo $field->getControlGroup(); ?>
		<?php endforeach; ?>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo JFactory::getApplication()->input->getCmd('return'); ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
