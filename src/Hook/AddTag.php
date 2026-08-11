<?php

namespace SimpleTasks\Hook;

use MWStake\MediaWiki\Component\GenericTagHandler\Hook\MWStakeGenericTagHandlerInitTagsHook;
use SimpleTasks\Tag\TaskReportTag;

class AddTag implements MWStakeGenericTagHandlerInitTagsHook {

	/**
	 * @inheritDoc
	 */
	public function onMWStakeGenericTagHandlerInitTags( array &$tags ) {
		$tags[] = new TaskReportTag();
	}
}
