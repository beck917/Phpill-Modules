<?php
/**
 * Help task to display general instructons and list all tasks
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Kohana Team
 * @copyright  (c) 2009-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
namespace Phpill\Modules\Minion\Libraries;
class Help extends Task
{
	/**
	 * Generates a help list for all tasks
	 *
	 * @return null
	 */
	protected function _execute(array $params)
	{
		$tasks = $this->_compile_task_list(\Phpill::list_files('classes/Task'));

		$view = new \Phpill\Libraries\View('minion/help/list');

		$view->tasks = $tasks;

		echo $view;
	}
}
