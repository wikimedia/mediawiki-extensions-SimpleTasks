<?php

namespace SimpleTasks\Hook;

use MediaWiki\Extension\UnifiedTaskOverview\TaskStore;
use MediaWiki\Language\Language;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\TitleFactory;
use SimpleTasks\SimpleTask;
use SimpleTasks\SimpleTasksTaskDescriptor;

class UpdateUnifiedTaskOverview implements SimpleTasksPersistTaskHook, SimpleTasksDeleteTaskHook {

	/**
	 * @param Language $language
	 * @param TitleFactory $titleFactory
	 */
	public function __construct(
		private readonly Language $language,
		private readonly TitleFactory $titleFactory
	) {
	}

	/**
	 * @param SimpleTask $task
	 * @return void
	 * @throws \Throwable
	 */
	public function onSimpleTasksDeleteTask( SimpleTask $task ): void {
		$descriptor = new SimpleTasksTaskDescriptor( $task, $this->language, $this->titleFactory );
		$this->getTaskStore()?->deleteTask( $descriptor, $task->getUser() );
	}

	/**
	 * @param SimpleTask $task
	 * @return void
	 * @throws \Throwable
	 */
	public function onSimpleTasksPersistTask( SimpleTask $task ): void {
		if ( $task->isCompleted() ) {
			$this->onSimpleTasksDeleteTask( $task );
			return;
		}
		$descriptor = new SimpleTasksTaskDescriptor( $task, $this->language, $this->titleFactory );
		$this->getTaskStore()?->storeTask( $descriptor, $task->getUser() );
	}

	/**
	 * @return TaskStore|null
	 */
	private function getTaskStore(): ?TaskStore {
		$services = MediaWikiServices::getInstance();
		return $services->hasService( 'UnifiedTaskOverview.TaskStore' ) ?
			$services->getService( 'UnifiedTaskOverview.TaskStore' ) : null;
	}
}
