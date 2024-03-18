<?php

namespace SimpleTasks\Tests;

use AtMentions\MentionParser;
use DateTime;
use DateTimeTools\DateTimeParser;
use MediaWiki\Extension\Checklists\ChecklistItem;
use MediaWiki\Extension\Checklists\ChecklistManager;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MWStake\MediaWiki\Component\Events\Notifier;
use MWStake\MediaWiki\Component\Notifications\NullNotifier;
use PHPUnit\Framework\TestCase;
use SimpleTasks\SimpleTaskManager;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LBFactory;

class ManagerTest extends TestCase {

	/**
	 * @param ChecklistItem $checklistItem
	 * @param array|null $expected
	 *
	 * @dataProvider provideData
	 * @covers \SimpleTasks\SimpleTaskManager::processTask
	 *
	 * @return void
	 */
	public function testProcessTasks( ChecklistItem $checklistItem, ?array $expected ) {
		$manager = new SimpleTaskManager(
			$this->getLoadBalancerMock(),
			$this->getChecklistManagerMock(),
			$this->getUserFactoryMock(),
			$this->getMentionParserMock(),
			$this->getDateTimeParserMock(),
			new Notifier( [], $this->createMock( LBFactory::class ) ),
			new NullNotifier
		);
		$task = $manager->processTask( $checklistItem );
		if ( $expected === null ) {
			$this->assertNull( $task );
		} else {
			$data = $task->jsonSerialize();
			$this->assertEquals( $expected, [
				'text' => $data['text'], 'assignee' => 'Bar', 'dueDate' => $data['dueDate']
			] );
		}
	}

	/**
	 * @return array[]
	 */
	public function provideData() {
		return [
			'empty' => [
				$this->checkChecklistItemMock( '' ),
				null,
			],
			'without-mention' => [
				$this->checkChecklistItemMock( 'foo' ),
				null,
			],
			'with-mention' => [
				$this->checkChecklistItemMock( 'Foo [[User:Bar]]' ),
				[
					'text' => 'Foo',
					'assignee' => 'Bar',
					'dueDate' => null,
				],
			],
			'with-mention-and-duedate' => [
				$this->checkChecklistItemMock( 'Foo [[User:Bar]] brown fox <datetime>2022/01/01</datetime>' ),
				[
					'text' => 'Foo brown fox',
					'assignee' => 'Bar',
					'dueDate' => '20220101000000',
				],
			],
		];
	}

	private function getLoadBalancerMock() {
		return $this->createMock( ILoadBalancer::class );
	}

	private function getChecklistManagerMock() {
		return $this->createMock( ChecklistManager::class );
	}

	private function getUserFactoryMock() {
		return $this->createMock( UserFactory::class );
	}

	private function getMentionParserMock() {
		$mock = $this->createMock( MentionParser::class );
		$mock->method( 'parse' )->willReturnCallback( function ( $text ) {
			if ( strpos( $text, '[[User:Bar]]' ) !== false ) {
				$userMock = $this->createMock( UserIdentity::class );
				$userMock->method( 'getName' )->willReturn( 'Bar' );
				return [ [ 'text' => '[[User:Bar]]', 'user' => $userMock ] ];
			}
			return [];
		} );
		return $mock;
	}

	private function getDateTimeParserMock() {
		$mock = $this->createMock( DateTimeParser::class );
		$mock->method( 'parse' )->willReturnCallback( function ( $text ) {
			if ( strpos( $text, '<datetime>' ) !== false ) {
				$dateTimeMock = $this->createMock( DateTime::class );
				$dateTimeMock->method( 'format' )->willReturn( '20220101000000' );
				return [ [ 'text' => '<datetime>2022/01/01</datetime>', 'datetime' => $dateTimeMock ] ];
			}
			return [];
		} );
		return $mock;
	}

	private function checkChecklistItemMock( string $text, $value = 'checked' ) {
		$mock = $this->createMock( ChecklistItem::class );
		$mock->method( 'getText' )->willReturn( $text );
		$mock->method( 'getValue' )->willReturn( $value );
		$mock->method( 'getId' )->willReturn( uniqid() );
		$mock->method( 'getPage' )->willReturn( $this->createMock( \Title::class ) );

		return $mock;
	}
}
