## MediaWiki Stakeholders Group - Components
# 3rdPartyResources for MediaWiki

Provides ResourceLoader module definitions that allow for inclusion of third-party pre-compiled resources (JS/CSS).

**This code is meant to be used within the MediaWiki framework. Do not attempt to use it outside of MediaWiki.**

## Use in a MediaWiki extension

Add `"mwstake/mediawiki-component-3rdpartyresources": "~1.0"` to the `require` section of your `composer.json` file.

Explicit initialization is required. This can be archived by
- either adding `"callback": "mwsInitComponents"` to your `extension.json`/`skin.json`
- or calling `mwsInitComponents();` within you extensions/skins custom `callback` method

See also [`mwstake/mediawiki-componentloader`](https://github.com/hallowelt/mwstake-mediawiki-componentloader).

### Optional: Setting up `package.json`

In MediaWiki extensions/skins, the `package.json` is usually just used for CI tasks, like `eslint`. Therefore one usually has only the `"devDependecies"` entry and the `node_modules/` directory is excluded from source code management e.g. via `.gitignore`.

If `npm` is used to pull in actual dependenies, it is recommended to copy the "dist" file(s) into the `resources/` (or `modules/`) directory of the extension/skin. This can easily be done by a `scripts.postinstall` entry.

Example `package.json`

```json
"scripts": {
	"postinstall": "cp node_modules/some-package/dist/some-package.min.js resources/lib/"
},
"dependencies": {
	"some-package": "^1.0.0"
}
```

### Setting up the ResourceLoader module

MediaWiki ResourceLoader may currupt pre-packaged JS/CSS files, as it applies "minification". Also it may lack a proper `module.exports` statement, to make the third party code available to the consuming code.

To overcome such issues, use the `"class"` property of the `"ResourceModule"` definition like this:

```json
"ResourceModules": {
	"ext.VTreeView": {
		"class": "MWStake\\MediaWiki\\Component\\3rdPartyResources\\ResourceLoader\\DistFiles",
		"packageFiles": [
			"lib/some-package.min.js"
		]
	}
},
"ResourceFileModulePaths": {
	"localBasePath": "resources",
	"remoteExtPath": "MyExtension/resources"
},
```

If a special `module.exports` is required, it can be specified in the `"module.exports"` property.

```json
"ResourceModules": {
	"ext.VTreeView": {
		"class": "MWStake\\MediaWiki\\Component\\3rdPartyResources\\ResourceLoader\\DistFiles",
		"module.exports": "SomePackageComponent",
		"packageFiles": [
			"lib/some-package.min.js"
		]
	}
},
```