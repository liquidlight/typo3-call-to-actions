<?php

use LiquidLight\CallToActions\Backend\CallToActionItemsProcFunc;
use LiquidLight\CallToActions\Userfunc\Tca;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

return [
	'ctrl' => [
		'title' => 'LLL:EXT:call_to_actions/Resources/Private/Language/locallang.xlf:call_to_action',
		'label' => 'label',
		'label_userFunc' => Tca::class . '->getCallToActionLabel',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'versioningWS' => true,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diff',
		'default_sortby' => 'ORDER BY title ASC',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
			'fe_group' => 'fe_group',
		],
		'typeicon_classes' => [
			'default' => 'liquidlight_call_to_actions',
		],
		'searchFields' => '
			uid,
			label,
			title,
			content
		',
	],
	'interface' => [
		'maxDBListItems' => 50,
		'maxSingleDBListItems' => 100,
	],
	'columns' => [

		'sys_language_uid' => [
			'exclude' => true,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
			'config' => ['type' => 'language'],
		],
		'l10n_parent' => [
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'default' => 0,
				'items' => [
					['', 0],
				],
				'foreign_table' => 'tx_calltoactions_domain_model_calltoactions',
				'foreign_table_where' => 'AND {#tx_calltoactions_domain_model_calltoactions}.{#pid}=###CURRENT_PID### AND {#tx_calltoactions_domain_model_calltoactions}.{#sys_language_uid} IN (-1,0)',
			],
		],
		'l10n_diffsource' => [
			'config' => [
				'type' => 'passthrough',
			],
		],
		't3ver_label' => [
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			],
		],
		'hidden' => [
			'exclude' => true,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
			'config' => [
				'type' => 'check',
				'renderType' => 'checkboxToggle',
				'items' => [
					[
						0 => '',
						1 => '',
						'invertStateDisplay' => true,
					],
				],
			],
		],

		// General
		'label' => [
			'label' => 'LLL:EXT:call_to_actions/Resources/Private/Language/locallang.xlf:label',
			'description' => 'LLL:EXT:call_to_actions/Resources/Private/Language/locallang.xlf:label.description',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'required,trim',
			],
		],

		'type' => [
			'l10n_mode' => 'exclude',
			'label' => 'LLL:EXT:call_to_actions/Resources/Private/Language/locallang.xlf:type',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [],
				'itemsProcFunc' => CallToActionItemsProcFunc::class . '->getTypeClasses',
			],
		],
		'theme' => [
			'l10n_mode' => 'exclude',
			'label' => 'LLL:EXT:call_to_actions/Resources/Private/Language/locallang.xlf:theme',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [],
				'itemsProcFunc' => CallToActionItemsProcFunc::class . '->getThemeClasses',
			],
		],

		'title' => [
			'label' => 'LLL:EXT:call_to_actions/Resources/Private/Language/locallang.xlf:title',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
			],
		],
		'content' => [
			'label' => 'LLL:EXT:call_to_actions/Resources/Private/Language/locallang.xlf:content',
			'config' => [
				'type' => 'text',
				'cols' => 48,
				'rows' => 5,
			],
		],
		'url' => [
			'label' => 'LLL:EXT:call_to_actions/Resources/Private/Language/locallang.xlf:url',
			'config' => [
				'type' => 'input',
				'renderType' => 'inputLink',
				'softref' => 'typolink',
				'size' => 30,
				'max' => 255,
				'checkbox' => '',
				'eval' => 'trim',
			],
		],
		'button' => [
			'label' => 'LLL:EXT:call_to_actions/Resources/Private/Language/locallang.xlf:button',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
			],
		],

		'image' => [
			'l10n_mode' => 'exclude',
			'label' => 'LLL:EXT:call_to_actions/Resources/Private/Language/locallang.xlf:image',
			'config' => ExtensionManagementUtility::getFileFieldTCAConfig('image', [
				'appearance' => [
					'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
				],
				'maxitems' => 1,
				'size' => 1,

				'overrideChildTca' => ['types' => [
					File::FILETYPE_IMAGE => [
						'showitem' => '
						alternative,crop,
						--palette--;;filePalette',
					],
				]],
			], $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']),
		],

	],

	'palettes' => [
		'tx_call_to_action_layout' => [
			'label' => 'LLL:EXT:call_to_actions/Resources/Private/Language/locallang.xlf:tx_call_to_action_layout',
			'showitem' => 'type, theme',
		],
		'tx_call_to_action_content' => [
			'label' => 'LLL:EXT:call_to_actions/Resources/Private/Language/locallang.xlf:tx_call_to_action_content',
			'showitem' => 'title, --linebreak--, content',
		],
		'tx_call_to_action_ct_action' => [
			'label' => 'LLL:EXT:call_to_actions/Resources/Private/Language/locallang.xlf:tx_call_to_action_ct_action',
			'showitem' => 'button, url',
		],
	],

	'types' => [
		0 => [
			'showitem' => '
			--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
				label,
				--palette--;;tx_call_to_action_layout,
				--palette--;;tx_call_to_action_content,
				--palette--;;tx_call_to_action_ct_action,
				image,
			--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.access,
				hidden,
				starttime,
				endtime,
				fe_group,
			--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
				sys_language_uid,
				l10n_parent,
				l10n_diff
			',
			'columnsOverrides' => [
				'content' => [
					'config' => [
						'enableRichtext' => 1,
						'richtextConfiguration' => 'default',
					],
				],
			],
		],
	],
];
