<?php

/**
 * CallToActionItemsProcFunc
 *
 * Dynamically generate option dropdowns for the flexform
 *
 * @author Mike Street <mike@liquidlight.co.uk>
 * @copyright Liquid Light Ltd.
 * @package TYPO3
 * @subpackage call_to_actions
 */

namespace LiquidLight\CallToActions\Backend;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class CallToActionItemsProcFunc
{
	/**
	 * getConfig
	 *
	 * Load the defined classes from setup.ts
	 *
	 */
	public function getConfig(): array
	{
		$configurationManager = GeneralUtility::makeInstance(ConfigurationManagerInterface::class);
		$setup = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
		return $setup['tt_content.']['call_to_actions.']['classes.'] ?? [];
	}

	/**
	 * getClasses
	 *
	 * Use the key to generate an array of classes defined in Typoscript
	 *
	 */
	public function getClasses(string $key): array
	{
		$output = [];
		if (isset($this->getConfig()[$key])) {
			foreach ($this->getConfig()[$key] as $index => $class) {
				$output[(int)rtrim($index, '.')] = [
					$class['title'],
					$class['value'],
				];
			}
		}

		ksort($output);

		return array_values($output);
	}

	/**
	 * getTypeClasses
	 *
	 * Returns "type" classes for boxes
	 *
	 * @param array &$config configuration array
	 */
	public function getTypeClasses(array &$config)
	{
		$config['items'] = $this->getClasses('type.');
	}

	/**
	 * wrapper
	 *
	 * Returns "wrapper" classes for boxes
	 *
	 * @param array &$config configuration array
	 */
	public function getThemeClasses(array &$config)
	{
		$config['items'] = $this->getClasses('theme.');
	}
}
