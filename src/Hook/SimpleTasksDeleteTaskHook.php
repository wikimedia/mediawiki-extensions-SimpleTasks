<?php

namespace SimpleTasks\Hook;

use SimpleTasks\SimpleTask;

interface SimpleTasksDeleteTaskHook {

	/**
	 * @param SimpleTask $task
	 * @return void
	 */
	public function onSimpleTasksDeleteTask( SimpleTask $task ): void;
}
