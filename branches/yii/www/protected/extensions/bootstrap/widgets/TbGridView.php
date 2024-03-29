<?php

/**
 * TbGridView class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package bootstrap.widgets
 */
Yii::import('zii.widgets.grid.CGridView');
Yii::import('bootstrap.widgets.TbDataColumn');

/**
 * Bootstrap Zii grid view.
 */
class TbGridView extends CGridView {
    // Table types.

    const TYPE_STRIPED = 'striped';
    const TYPE_BORDERED = 'bordered';
    const TYPE_CONDENSED = 'condensed';

    /**
     * @var string|array the table type.
     * Valid values are 'striped', 'bordered' and/or ' condensed'.
     */
    public $type;

    /**
     * @var string the CSS class name for the pager container. Defaults to 'pagination'.
     */
    public $pagerCssClass = 'pagination';

    /**
     * @var array the configuration for the pager.
     * Defaults to <code>array('class'=>'ext.bootstrap.widgets.TbPager')</code>.
     */
    public $pager = array('class' => 'bootstrap.widgets.TbPager');

    /**
     * @var string the URL of the CSS file used by this grid view.
     * Defaults to false, meaning that no CSS will be included.
     */
    public $cssFile = false;

    /**
     * Initializes the widget.
     */
    public function init() {
        parent::init();

        $classes = array('table');

        if (isset($this->type)) {
            if (is_string($this->type))
                $this->type = explode(' ', $this->type);

            $validTypes = array(self::TYPE_STRIPED, self::TYPE_BORDERED, self::TYPE_CONDENSED);

            if (!empty($this->type)) {
                foreach ($this->type as $type) {
                    if (in_array($type, $validTypes))
                        $classes[] = 'table-' . $type;
                }
            }
        }

        if (!empty($classes)) {
            $classes = implode(' ', $classes);
            if (isset($this->itemsCssClass))
                $this->itemsCssClass .= ' ' . $classes;
            else
                $this->itemsCssClass = $classes;
        }

        $popover = Yii::app()->bootstrap->popoverSelector;
        $tooltip = Yii::app()->bootstrap->tooltipSelector;

        $afterAjaxUpdate = "js:function() {
			jQuery('.popover').remove();
			jQuery('{$popover}').popover();
			jQuery('.tooltip').remove();
			jQuery('{$tooltip}').tooltip();
		}";

        if (!isset($this->afterAjaxUpdate))
            $this->afterAjaxUpdate = $afterAjaxUpdate;
    }

    /**
     * Creates column objects and initializes them.
     */
    protected function initColumns() {
        foreach ($this->columns as $i => $column) {
            if (is_array($column) && !isset($column['class']))
                $this->columns[$i]['class'] = 'bootstrap.widgets.TbDataColumn';
        }

        parent::initColumns();
    }

    /**
     * Creates a column based on a shortcut column specification string.
     * @param mixed $text the column specification string
     * @return \TbDataColumn|\CDataColumn the column instance
     * @throws CException if the column format is incorrect
     */
    protected function createDataColumn($text) {
        if (!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/', $text, $matches))
            throw new CException(Yii::t('zii', 'The column must be specified in the format of "Name:Type:Label", where "Type" and "Label" are optional.'));

        $column = new TbDataColumn($this);
        $column->name = $matches[1];

        if (isset($matches[3]) && $matches[3] !== '')
            $column->type = $matches[3];

        if (isset($matches[5]))
            $column->header = $matches[5];

        return $column;
    }

    public function renderTableHeader() {
        if (!$this->hideHeader) {
            echo "<thead>\n";

            if ($this->filterPosition === self::FILTER_POS_HEADER)
                $this->renderFilter();

            echo "<tr>\n";
            foreach ($this->columns as $column) {
                if ($column instanceof TbDataColumn) {
                    if ($column->headerVisible) {
                        $column->renderHeaderCell();
                    }
                } else {
                    $column->renderHeaderCell();
                }
            }
            echo "</tr>\n";

            if ($this->filterPosition === self::FILTER_POS_BODY)
                $this->renderFilter();

            echo "</thead>\n";
        }
        else if ($this->filter !== null && ($this->filterPosition === self::FILTER_POS_HEADER || $this->filterPosition === self::FILTER_POS_BODY)) {
            echo "<thead>\n";
            $this->renderFilter();
            echo "</thead>\n";
        }
    }

}
