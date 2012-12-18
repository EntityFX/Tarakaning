<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FilterColumnBase
 *
 * @author Artem
 */
abstract class FilterColumnBase extends CGridColumn {

    public $filterInputHtmlOptions = array();

    /**
     * @var string the attribute name of the data model. Used for column sorting, filtering and to render the corresponding
     * attribute value in each data cell. If {@link value} is specified it will be used to rendered the data cell instead of the attribute value.
     * @see value
     * @see sortable
     */
    public $name;

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
     * Renders the filter cell.
     */
    public function renderFilterCell() {
        echo CHtml::openTag('td', $this->filterHtmlOptions);
        echo '<div class="filter-container">';
        $this->renderFilterCellContent();
        echo '</div>';
        echo CHtml::closeTag('td');
    }

    /**
     * Renders the filter cell content.
     * This method will render the {@link filter} as is if it is a string.
     * If {@link filter} is an array, it is assumed to be a list of options, and a dropdown selector will be rendered.
     * Otherwise if {@link filter} is not false, a text field is rendered.
     * @since 1.1.1
     */
    protected function renderFilterCellContent() {
        if (is_string($this->filter))
            echo $this->filter;
        elseif ($this->filter !== false && $this->grid->filter !== null && $this->name !== null && strpos($this->name, '.') === false) {
            if (is_array($this->filter))
                echo CHtml::activeDropDownList($this->grid->filter, $this->name, $this->filter, array('id' => false, 'prompt' => ''));
            elseif ($this->filter === null) {
                $filterHtmlOptions = array_merge(array('id' => false), $this->filterInputHtmlOptions);
                echo CHtml::activeTextField($this->grid->filter, $this->name, $filterHtmlOptions);
            }
        }
        else
            parent::renderFilterCellContent();
    }

    /**
     * Renders the header cell content.
     * This method will render a link that can trigger the sorting if the column is sortable.
     */
    protected function renderHeaderCellContent() {
        if ($this->grid->enableSorting && $this->sortable && $this->name !== null) {
            $sort = $this->grid->dataProvider->getSort();
            $label = isset($this->header) ? $this->header : $sort->resolveLabel($this->name);

            if ($sort->resolveAttribute($this->name) !== false)
                $label .= '<span class="caret"></span>';

            echo $sort->link($this->name, $label, array('class' => 'sort-link'));
        }
        else {
            if ($this->name !== null && $this->header === null) {
                if ($this->grid->dataProvider instanceof CActiveDataProvider)
                    echo CHtml::encode($this->grid->dataProvider->model->getAttributeLabel($this->name));
                else
                    echo CHtml::encode($this->name);
            }
            else
                parent::renderHeaderCellContent();
        }
    }

}

?>
