<?php

namespace SimpleTasks\Notifications;

use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\Notifications\INotifier;

class Register {
	public static function registerNotifications() {
		/** @var INotifier $notifier */
		$notifier = MediaWikiServices::getInstance()->getService( 'MWStakeNotificationsNotifier' );

		$notifier->registerNotificationCategory( 'simple-tasks-notification-cat', [
			'tooltip' => 'simple-tasks-notification-cat-tooltip',
		] );
		$notifier->registerNotification(
			'simple-tasks-task-echo',
			[
				'category' => 'simple-tasks-notification-cat',
				'summary-params' => [
					'title'
				],
				'email-subject-params' => [
					'title'
				],
				'email-body-params' => [
					'title', 'agent', 'realname', 'taskText'
				],
				'web-body-params' => [
					'agent', 'realname', 'taskText'
				],
				'summary-message' => 'simple-tasks-echo-notification-task-subject',
				'email-subject-message' => 'simple-tasks-echo-notification-task-subject',
				'email-body-message' => 'simple-tasks-echo-notification-task-email-body',
				'web-body-message' => 'simple-tasks-echo-notification-task-web-body',
			]
		);
	}
}
