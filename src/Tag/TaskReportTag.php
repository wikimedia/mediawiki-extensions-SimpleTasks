<?php

namespace SimpleTasks\Tag;

use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\GenericTagHandler\GenericTag;
use MWStake\MediaWiki\Component\GenericTagHandler\ITagHandler;
use MWStake\MediaWiki\Component\InputProcessor\Processor\StringValue;

class TaskReportTag extends GenericTag {

	/**
	 * @inheritDoc
	 */
	public function getTagNames(): array {
		return [ 'taskreport' ];
	}

	/**
	 * @inheritDoc
	 */
	public function getHandler( MediaWikiServices $services ): ITagHandler {
		return new TaskReportTagHandler();
	}

	/**
	 * @inheritDoc
	 */
	public function hasContent(): bool {
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getParamDefinition(): ?array {
		// Due to the version used those must be strings.
		// Parsing is done in the handler.
		return [
			'user' => ( new StringValue() )->setDefaultValue( '' ),
			'date' => ( new StringValue() )->setDefaultValue( '' ),
			'status' => ( new StringValue() )->setDefaultValue( '' ),
			'namespace' => ( new StringValue() )->setDefaultValue( '' ),
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getContainerElementName(): ?string {
		return 'div';
	}

	/**
	 * @inheritDoc
	 */
	public function shouldParseInput(): bool {
		return true;
	}
}
