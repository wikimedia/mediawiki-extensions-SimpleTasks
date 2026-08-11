<?php

namespace SimpleTasks\Tag;

use MediaWiki\Html\Html;
use MediaWiki\Json\FormatJson;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\PPFrame;
use MWStake\MediaWiki\Component\GenericTagHandler\ITagHandler;

class TaskReportTagHandler implements ITagHandler {

	/**
	 * @var array
	 */
	private static $counter = [];

	/**
	 * @param string $input
	 * @param array $params
	 * @param Parser $parser
	 * @param PPFrame $frame
	 * @return string
	 */
	public function getRenderedContent( string $input, array $params, Parser $parser, PPFrame $frame ): string {
		// Add a specific id to each list.
		if ( isset( static::$counter[spl_object_id( $parser )] ) ) {
			static::$counter[spl_object_id( $parser )]++;
		} else {
			static::$counter[spl_object_id( $parser )] = 0;
		}

		// Due to the version we are parsing strings here.
		$filter = [];
		if ( !empty( $params['user'] ) ) {
			$filter['user'] = $params['user'];
		}

		if ( !empty( $params['date'] ) ) {
			$filter['date'] = $params['date'];
		}

		if ( !empty( $params['status'] ) ) {
			$filter['state'] = $params['status'];
		}

		if ( !empty( $params['namespaces'] ) ) {
			$filter['namespace'] = $params['namespaces'];
		}

		foreach ( $filter as $key => $value ) {
			$filter[$key] = $parser->recursiveTagParse( $value, $frame );
		}

		$parser->getOutput()->addModuleStyles( [ 'ext.simpletasks.taskreport.styles' ] );
		$parser->getOutput()->addModules( [ 'ext.simpletasks.taskreport' ] );
		$count = static::$counter[spl_object_id( $parser )];

		return Html::element( 'div', [
			'class' => 'task-report load',
			'data-filter' => FormatJson::encode( $filter ),
			'data-no' => $count,
		] );
	}
}
