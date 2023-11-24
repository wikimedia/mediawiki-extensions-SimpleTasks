<?php

namespace SimpleTasks\Hook;

use EchoEvent;
use MediaWiki\User\UserFactory;
use SimpleTasks\SimpleTaskManager;
use Title;
use User;

class AbortMentionNotification {

	/**
	 * @var UserFactory
	 */
	private $userFactory;

	/**
	 * @var SimpleTaskManager
	 */
	private $taskManager;

	/**
	 * @param UserFactory $userFactory
	 * @param SimpleTaskManager $taskManager
	 */
	public function __construct( UserFactory $userFactory, SimpleTaskManager $taskManager ) {
		$this->userFactory = $userFactory;
		$this->taskManager = $taskManager;
	}

	/**
	 * @param User $user
	 * @param EchoEvent $event
	 *
	 * @return bool
	 */
	public function onEchoAbortEmailNotification( $user, $event ) {
		if ( $event->getType() !== 'at-mentions-mention-echo' ) {
			return true;
		}
		if ( $this->hasActiveTask( $user, $event->getTitle() ) ) {
			// Abort mention notification - send to no one
			return false;
		}
		return true;
	}

	/**
	 * @param EchoEvent &$event
	 * @param User $user
	 * @param string $type
	 *
	 * @return void
	 * @throws \MWException
	 */
	public function onBlueSpiceEchoConnectorNotifyBeforeSend( &$event, $user, $type ) {
		if ( $event->getType() !== 'at-mentions-mention-echo' || $type !== 'web' ) {
			return;
		}
		if ( $this->hasActiveTask( $user, $event->getTitle() ) ) {
			// Replace actual mention event with a dummy one
			$event = EchoEvent::create( [ 'type' => '-invalid-' ] );
		}
	}

	/**
	 * @param User $user
	 * @param Title $title
	 *
	 * @return bool
	 */
	private function hasActiveTask( User $user, Title $title ): bool {
		return !empty( $this->taskManager->forTitle( $title )->forUser( $user )->query( [ 'st_completed' => 0 ] ) );
	}
}
