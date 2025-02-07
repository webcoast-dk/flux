<?php
namespace FluidTYPO3\Flux\ViewHelpers\Grid;

/*
 * This file is part of the FluidTYPO3/Flux project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use FluidTYPO3\Flux\Form\Container\Column;
use FluidTYPO3\Flux\ViewHelpers\AbstractFormViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Flexform Grid Column ViewHelper
 *
 * Used inside `<flux:grid.row>` tags.
 *
 * Use the `colPos` attribute for grids in page and content elements.
 *
 * See `<flux:grid>` for an example.
 *
 * ### Limit allowed elements
 *
 * It is possible to limit the elements allowed in the column by setting
 * the `allowedContentTypes` variable:
 *
 *     <flux:grid.column name="elements" colPos="0">
 *         <flux:form.variable name="allowedContentTypes" value="text,shortcut"/>
 *     </flux:grid.column>
 *
 * The value is a comma-separated list of content type IDs; they can be found
 * in `tt_content.CType` column.
 *
 * ### Limit allowed fluid content elements
 *
 * It is also possible to limit the allowed fluid content elements:
 *
 *     <flux:grid.column name="elements" colPos="0">
 *         <flux:form.variable name="allowedContentTypes" value="extkey_vehicledetailssectionusedcarseal"/>
 *     </flux:grid.column>
 */
class ColumnViewHelper extends AbstractFormViewHelper
{

    /**
     * Initialize
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument(
            'name',
            'string',
            'Identifies your column and is used to fetch translations from XLF for example.',
            false,
            'column'
        );
        $this->registerArgument('label', 'string', 'Optional column label, will be shown as column header.');
        $this->registerArgument('colPos', 'integer', 'Column number - between 0 and 99, should be unique.', true);
        $this->registerArgument('colspan', 'integer', 'Column span', false, 1);
        $this->registerArgument('rowspan', 'integer', 'Row span', false, 1);
        $this->registerArgument('style', 'string', 'Inline style to add when rendering the column');
        $this->registerArgument(
            'variables',
            'array',
            'Freestyle variables which become assigned to the resulting Component - can then be read from that ' .
            'Component outside this Fluid template and in other templates using the Form object from this template. ' .
            'Can also be set and/or overridden in tag content using `<flux:form.variable />`',
            false,
            []
        );
        $this->registerArgument(
            'extensionName',
            'string',
            'If provided, enables overriding the extension context for this and all child nodes. The extension ' .
            'name is otherwise automatically detected from rendering context.'
        );
    }

    /**
     * @param RenderingContextInterface $renderingContext
     * @param iterable $arguments
     * @return Column
     */
    public static function getComponent(RenderingContextInterface $renderingContext, iterable $arguments)
    {
        $column = static::getContainerFromRenderingContext($renderingContext)->createContainer(Column::class, $arguments['name'], $arguments['label']);
        $column->setExtensionName(
            static::getExtensionNameFromRenderingContextOrArguments($renderingContext, $arguments)
        );
        $column->setColspan($arguments['colspan']);
        $column->setRowspan($arguments['rowspan']);
        $column->setStyle($arguments['style']);
        $column->setColumnPosition($arguments['colPos']);
        $column->setVariables($arguments['variables']);
        return $column;
    }
}
