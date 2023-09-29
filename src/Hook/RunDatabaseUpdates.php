<?php

namespace SimpleTasks\Hook;

use DatabaseUpdater;
use MediaWiki\Installer\Hook\LoadExtensionSchemaUpdatesHook;

class RunDatabaseUpdates implements LoadExtensionSchemaUpdatesHook {

	/**
	 * @param DatabaseUpdater $updater
	 *
	 * @return bool|void
	 */
	public function onLoadExtensionSchemaUpdates( $updater ) {
		$updater->addExtensionTable( 'simple_tasks', __DIR__ . '/../../db/simple_tasks.sql' );
	}
}
