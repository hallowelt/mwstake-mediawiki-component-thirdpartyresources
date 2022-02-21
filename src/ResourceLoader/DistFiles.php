<?php

namespace MWStake\MediaWiki\Component\3rdPartyResources\ResourceLoader;

use ResourceLoaderContext;
use ResourceLoaderFileModule;

class DistFiles extends ResourceLoaderFileModule {

	/**
	 * @inheritDoc
	 */
	public function getPackageFiles( ResourceLoaderContext $context ) {
		$package = parent::getPackageFiles( $context );

		$modifiedFiles = [];
		foreach ( $package['files'] as $filename => $file ) {
			$modifiedFiles[$filename] = $file;

			$modifiedContent = preg_replace( "|//# sourceMappingURL=.*?$|s", '', $file['content'] );
			$modifiedFiles[$filename]['content'] = trim( $modifiedContent . ';module.exports=Vuex;' );
		}
		$package['files'] = $modifiedFiles;

		return $package;
	}
}