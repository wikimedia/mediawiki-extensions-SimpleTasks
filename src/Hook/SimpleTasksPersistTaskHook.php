<?php

namespace SimpleTasks\Hook;

use SimpleTasks\SimpleTask;

interface SimpleTasksPersistTaskHook {

	/**
	 * @param SimpleTask $task
	 * @return void
	 */
	public function onSimpleTasksPersistTask( SimpleTask $task ): void;
}
