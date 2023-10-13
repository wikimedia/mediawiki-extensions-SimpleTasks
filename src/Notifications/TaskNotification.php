<?php

namespace SimpleTasks\Notifications;

use MWStake\MediaWiki\Component\Notifications\BaseNotification;
use SimpleTasks\SimpleTask;
use Title;
use User;

class TaskNotification extends BaseNotification {

	/** @var string */
	private $text;

	/**
	 * @param SimpleTask $task
	 */
	public function __construct( SimpleTask $task ) {
		parent::__construct(
			'simple-tasks-task-echo',
			$task->getChecklistItem()->getAuthor() ?? User::newSystemUser( 'MediaWiki default' ),
			Title::castFromPageIdentity( $task->getChecklistItem()->getPage() )
		);
		$this->text = $task->getText();
		$this->addAffectedUsers( [ $task->getUser() ] );
	}

	/**
	 * @return array|string[]
	 */
	public function getParams() {
		return array_merge( parent::getParams(), [
			'realname' => $this->getUserRealName(),
			'taskText' => $this->text
		] );
	}
}
