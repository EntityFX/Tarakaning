<?php

/**
 * CDataColumn class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
Yii::import('zii.widgets.grid.CGridColumn');

/**
 * CDataColumn represents a grid view column that is associated with a data attribute or expression.
 *
 * Either {@link name} or {@link value} should be specified. The former specifies
 * a data attribute name, while the latter a PHP expression whose value should be rendered instead.
 *
 * The property {@link sortable} determines whether the grid view can be sorted according to this column.
 * Note that the {@link name} should always be set if the column needs to be sortable. The {@link name}
 * value will be used by {@link CSort} to render a clickable link in the header cell to trigger the sorting.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package zii.widgets.grid
 * @since 1.1
 */
abstract class EntityFxDataColumnBase extends FilterColumnBase {

    /**
     * @var string the attribute name of the data model. Used for column sorting, filtering and to render the corresponding
     * attribute value in each data cell. If {@link value} is specified it will be used to rendered the data cell instead of the attribute value.
     * @see value
     * @see sortable
     */
    public $name;

    /**
     * @var string a PHP expression that will be evaluated for every data cell and whose result will be rendered
     * as the content of the data cells. In this expression, the variable
     * <code>$row</code> the row number (zero-based); <code>$data</code> the data model for the row;
     * and <code>$this</code> the column object.
     */
    public $value;

    /**
     * @var string the type of the attribute value. This determines how the attribute value is formatted for display.
     * Valid values include those recognizable by {@link CGridView::formatter}, such as: raw, text, ntext, html, date, time,
     * datetime, boolean, number, email, image, url. For more details, please refer to {@link CFormatter}.
     * Defaults to 'text' which means the attribute value will be HTML-encoded.
     */
    public $type = 'text';

    /**
     * @var boolean whether the column is sortable. If so, the header cell will contain a link that may trigger the sorting.
     * Defaults to true. Note that if {@link name} is not set, or if {@link name} is not allowed by {@link CSort},
     * this property will be treated as false.
     * @see name
     */
    public $sortable = true;

    /**
     * @var mixed the HTML code representing a filter input (eg a text field, a dropdown list)
     * that is used for this data column. This property is effective only when
     * {@link CGridView::filter} is set.
     * If this property is not set, a text field will be generated as the filter input;
     * If this property is an array, a dropdown list will be generated that uses this property value as
     * the list options.
     * If you don't want a filter for this data column, set this value to false.
     * @since 1.1.1
     */
    public $filter;

    /**
     * Initializes the column.
     */
    public function init() {
        parent::init();
        if ($this->name === null)
            $this->sortable = false;
        if ($this->name === null && $this->value === null)
            throw new CException(Yii::t('zii', 'Either "name" or "value" must be specified for CDataColumn.'));
    }

    /**
     * Renders the data cell content.
     * This method evaluates {@link value} or {@link name} and renders the result.
     * @param integer $row the row number (zero-based)
     * @param mixed $data the data associated with the row
     */
    protected function renderDataCellContent($row, $data) {
        if ($this->value !== null)
            $value = $this->evaluateExpression($this->value, array('data' => $data, 'row' => $row));
        elseif ($this->name !== null)
            $value = CHtml::value($data, $this->name);
        echo $value === null ? $this->grid->nullDisplay : $this->grid->getFormatter()->format($value, $this->type);
    }

}
