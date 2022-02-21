<?php

namespace MWStake\MediaWiki\Component\ThirdPartyResources\ResourceLoader;

use ResourceLoaderContext;
use ResourceLoaderFileModule;

class DistFiles extends ResourceLoaderFileModule {

	/**
	 * @var string
	 */
	private $moduleExports = '';

	/**
	 * @inheritDoc
	 */
	public function __construct(
		array $options = [],
		$localBasePath = null,
		$remoteBasePath = null
	) {
		if ( !empty( $options['module.exports'] ) ) {
			$this->moduleExports = $options['module.exports'];
		}
		parent::__construct( $options, $localBasePath, $remoteBasePath );
	}

	/**
	 * @var string
	 */
	private $currentFileContent = '';

	/**
	 * @inheritDoc
	 */
	public function getPackageFiles( ResourceLoaderContext $context ) {
		$package = parent::getPackageFiles( $context );

		$modifiedFiles = [];
		foreach ( $package['files'] as $filename => $file ) {
			$modifiedFiles[$filename] = $file;
			$this->currentFileContent = $file['content'];

			$this->removeSourceMappingURL();
			$this->maybeAddModuleExports();
			$this->trimFileContent();

			$modifiedFiles[$filename]['content'] = $this->currentFileContent;
		}
		$package['files'] = $modifiedFiles;

		return $package;
	}

	private function removeSourceMappingURL() {
		$this->currentFileContent = preg_replace(
			"|//# sourceMappingURL=.*?$|s",
			'',
			$this->currentFileContent
		);
	}

	private function maybeAddModuleExports() {
		if ( !empty( $this->moduleExports ) ) {
			$this->currentFileContent .= ";module.exports={$this->moduleExports};";
		}
	}

	private function trimFileContent() {
		$this->currentFileContent = trim( $this->currentFileContent );
	}
}
