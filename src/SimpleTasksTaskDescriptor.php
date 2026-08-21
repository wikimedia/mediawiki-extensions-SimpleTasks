<?php

namespace SimpleTasks;

use MediaWiki\Extension\UnifiedTaskOverview\ITaskDescriptor;
use MediaWiki\Language\Language;
use MediaWiki\Language\RawMessage;
use MediaWiki\Message\Message;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;

class SimpleTasksTaskDescriptor implements ITaskDescriptor {

	/**
	 * @param SimpleTask $task
	 * @param Language $language
	 * @param TitleFactory $titleFactory
	 */
	public function __construct(
		private readonly SimpleTask $task,
		private readonly Language $language,
		private readonly TitleFactory $titleFactory
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function getUniqueKey(): string {
		return $this->task->getChecklistItem()->getId();
	}

	/**
	 * @inheritDoc
	 */
	public function getTitle(): Title {
		$pageIdentity = $this->task->getChecklistItem()->getPage();
		return $this->titleFactory->castFromPageIdentity( $pageIdentity );
	}

	/**
	 * @return string
	 */
	public function getType(): string {
		return 'simple-tasks-task';
	}

	/**
	 * @return string
	 */
	public function getURL(): string {
		return $this->getTitle()->getFullURL();
	}

	/**
	 * @return Message
	 */
	public function getHeader(): Message {
		return new RawMessage(
			$this->task->getText()
		);
	}

	/**
	 * @return Message
	 */
	public function getSubHeader(): Message {
		$due = $this->task->getDueDate();
		if ( !$due ) {
			return new RawMessage( '' );
		}
		return Message::newFromKey(
			'simple-tasks-task-duedate',
			$this->language->userDate( $due->format( 'YmdHid' ), $this->task->getUser() )
		);
	}

	/**
	 * @return Message
	 */
	public function getBody(): Message {
		return Message::newFromKey( 'simple-tasks-task-desc' );
	}

	/**
	 * @return int
	 */
	public function getSortKey(): int {
		return 2;
	}

	/**
	 * @return array
	 */
	public function getRLModules(): array {
		return [];
	}

}
