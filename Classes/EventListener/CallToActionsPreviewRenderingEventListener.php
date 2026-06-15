<?php

namespace LiquidLight\CallToActions\EventListener;

use LiquidLight\CallToActions\Userfunc\Tca;
use TYPO3\CMS\Backend\View\Event\PageContentPreviewRenderingEvent;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Renders a backend page module preview listing the selected Call to Action records.
 *
 * Replaces the removed `tt_content_drawItem` hook.
 */
final class CallToActionsPreviewRenderingEventListener
{
	public function __invoke(PageContentPreviewRenderingEvent $event): void
	{
		if ($event->getTable() !== 'tt_content') {
			return;
		}

		$row = $event->getRecord();

		if (($row['CType'] ?? '') !== 'call_to_actions') {
			return;
		}

		$content = $this->generatePreview($row);

		if ($content !== '') {
			$event->setPreviewContent($content);
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
			->executeQuery()
			->fetchAllAssociative()
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

		return $content;
	}
}
