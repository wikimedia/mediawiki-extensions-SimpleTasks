<?php

namespace SimpleTasks;

use MediaWiki\Extension\UnifiedTaskOverview\ITaskDescriptor;
use MediaWiki\Language\Language;
use MediaWiki\Language\RawMessage;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Title\Title;
use stdClass;

class SimpleTasksTaskDescriptor implements ITaskDescriptor {

	/** @var SimpleTask */
	protected $task;
	/** @var Language */
	private $language;

	/**
	 * @param SimpleTask $task
	 * @param Language $language
	 */
	public function __construct( SimpleTask $task, Language $language ) {
		$this->task = $task;
		$this->language = $language;
	}

	/**
	 * @param stdClass $row
	 * @return static|null
	 */
	public static function newFromTaskRow( stdClass $row ): ?static {
		$services = MediaWikiServices::getInstance();
		/** @var SimpleTaskManager */
		$simpleTaskManager = $services->getService( 'SimpleTaskManager' );
		$simpleTaskManager = $simpleTaskManager->id( $row->uto_key );
		/** @var SimpleTask[] $tasks */
		$tasks = $simpleTaskManager->query();
		if ( !$tasks ) {
			return null;
		}

		return new static(
			$tasks[0],
			$services->getContentLanguage()
		);
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
		return MediaWikiServices::getInstance()->getTitleFactory()->newFromPageIdentity( $pageIdentity );
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
		return $this->task->getChecklistItem()->getPage()->getLocalURL();
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
