<?php

/**
 * Class 'CallToActionsElementPreviewRenderer' for the 'call_to_actions' extension.
 *
 * @author Mike Street <mike@liquidlight.co.uk>
 * @copyright Liquid Light Ltd.
 * @package TYPO3
 * @subpackage call_to_actions
 */

namespace LiquidLight\CallToActions\Hooks\PageLayoutView;

use LiquidLight\CallToActions\Userfunc\Tca;
use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Contains a preview rendering for the page module of CType="text"
 * @internal this is a concrete TYPO3 hook implementation and solely used for EXT:frontend and not part of TYPO3's Core API.
 */
class CallToActionsElementPreviewRenderer implements PageLayoutViewDrawItemHookInterface
{
	/**
	 * TYPO3 Content Hook
	 *
	 * @param \TYPO3\CMS\Backend\View\PageLayoutView $parentObject Calling parent object
	 * @param bool $drawItem Whether to draw the item using the default functionalities
	 * @param string $headerContent Header content
	 * @param string $itemContent Item content
	 * @param array $row Record row of tt_content
	 */
	public function preProcess(
		PageLayoutView &$parentObject,
		&$drawItem,
		&$headerContent,
		&$itemContent,
		array &$row
	) {
		// Is this a call_to_Actions plugin?
		if ($row['CType'] === 'call_to_actions') {
			// Generate the list of CTAs
			$content = $this->generatePreview($row);

			// If we have some CTAs selected
			if ((bool)$content) {
				// Prevent the default rendering
				$drawItem = false;

				// Re-add the header at the top
				$content = sprintf(
					'<strong>%s</strong><br>%s',
					$parentObject->CType_labels[$row['CType']],
					$content
				);

				// Append our build preview, linked to the edit
				$itemContent = $parentObject->linkEditContent($content, $row);
			}
		}
	}

	/**
	 * generatePreview
	 *
	 * Generates a list of records selected on the Call To Action
	 *
	 * @param  array $row Record row of tt_content
	 */
	protected function generatePreview(array $row): string
	{
		// Create a placeholder content

		// Get the CTAs associated with this tt_content element
		$content = '';
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
			->getQueryBuilderForTable('tx_calltoactions_domain_model_calltoactions')
		;
		$records = $queryBuilder
			->select('uid', 'label', 'type', 'theme')
			->from('tx_calltoactions_domain_model_calltoactions')
			->where(
				$queryBuilder->expr()->in(
					'uid',
					$queryBuilder->createNamedParameter(
						GeneralUtility::intExplode(',', $row['records']),
						Connection::PARAM_INT_ARRAY
					)
				)
			)
			->execute()
			->fetchAll()
		;

		// Check if we have some
		if (!count($records)) {
			return $content;
		}

		// Use the existing TCA label maker
		$tcaHelper = GeneralUtility::makeInstance(Tca::class);

		// Create a list of selected records
		$content .= '<ul style="padding: 0.2em 0 0 1.5em">';

		foreach ($records as $record) {
			$parameters = [
				'row' => $record,
				'title' => $record['label'], // Set a default
			];

			$tcaHelper->getCallToActionLabel($parameters);
			$content .= '<li>' . htmlspecialchars($parameters['title']) . '</li>';
		}

		$content .= '</ul>';

		// Return the preview content
		return $content;
	}
}
