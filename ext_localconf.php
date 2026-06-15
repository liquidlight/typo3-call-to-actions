<?php

defined('TYPO3') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
	"@import 'EXT:call_to_actions/Configuration/TsConfig/Page/Mod/Wizards/CallToActions.tsconfig'"
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
	'call_to_actions',
	'setup',
	"@import 'EXT:call_to_actions/Configuration/TypoScript/setup.typoscript'"
);
