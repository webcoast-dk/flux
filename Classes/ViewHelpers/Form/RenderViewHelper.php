<?php
namespace FluidTYPO3\Flux\ViewHelpers\Form;

/*
 * This file is part of the FluidTYPO3/Fluidbackend project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use FluidTYPO3\Flux\Form;
use FluidTYPO3\Flux\Service\FluxService;
use TYPO3\CMS\Backend\Form\FormEngine;
use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper as FluidFormViewHelper;

/**
 * ## Main form rendering ViewHelper
 *
 * Use to render a Flux form as HTML.
 */
class RenderViewHelper extends FluidFormViewHelper {

	/**
	 * @var FluxService
	 */
	protected $configurationService;

	/**
	 * @param FluxService $configurationService
	 * @return void
	 */
	public function injectConfigurationService(FluxService $configurationService) {
		$this->configurationService = $configurationService;
	}

	/**
	 * @param Form $form
	 * @return string
	 */
	public function render(Form $form) {
		$record = $form->getOption(Form::OPTION_RECORD);
		$table = $form->getOption(Form::OPTION_RECORD_TABLE);
		$field = $form->getOption(Form::OPTION_RECORD_FIELD);
		$node = $this->getNodeFactory()->create(array(
			'type' => 'flex',
			'renderType' => 'flex',
			'flexFormDataStructureArray' => $form->build(),
			'tableName' => $table,
			'fieldName' => $field,
			'databaseRow' => $record,
			'inlineStructure' => array(),
			'parameterArray' => array(
				'itemFormElName' => sprintf('data[%s][%d][%s]', $table, (integer) $record['uid'], $field),
				'itemFormElValue' => GeneralUtility::xml2array($record[$field]),
				'fieldChangeFunc' => array(),
				'fieldConf' => array(
					'config' => array(
						'ds' => $form->build()
					)
				)
			)
		));
		$output = $node->render();
		return $output['html'];
	}

	/**
	 * @return NodeFactory
	 */
	protected function getNodeFactory() {
		return new NodeFactory();
	}

}
