<?php
/**
 * @package     ACTParse
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <admin@bakual.net>
 * @copyright   © 2023 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die;
?>
<form action="<?php echo Route::_('index.php?option=com_actparse&view=info'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
		<?php echo Text::_('COM_ACTPARSE_INFO_PAGETEXT'); ?>
	</div>
</form>
