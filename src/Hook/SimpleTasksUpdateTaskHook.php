<?php

namespace SimpleTasks\Hook;

use MediaWiki\Extension\UnifiedTaskOverview\ITaskDescriptor;
use MediaWiki\User\User;

interface SimpleTasksUpdateTaskHook {

	/**
	 * @param ITaskDescriptor $descriptor
	 * @param User $user
	 * @param bool $isCompleted
	 * @return void
	 */
	public function onSimpleTasksUpdateTask(
		ITaskDescriptor $descriptor,
		User $user,
		bool $isCompleted
	): void;

}
