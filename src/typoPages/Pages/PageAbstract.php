<?php
namespace typoPages\Pages;

use yimaWidgetator\Widget\AbstractWidget;

/**
 * Class Simple
 *
 * @package typoPages\Pages
 */
abstract class PageAbstract extends AbstractWidget
{
    /**
     * Page Columns
     * : Assoc Array with `column_name` => (bool) is_translatable
     *
     * @var array
     */
    protected $columns = array(
        //'content' => true,
        //'style'   => false,
    );

    /**
     * Get Page Columns
     *
     * @return array
     */
    public function getColumns()
    {
        return array_keys($this->columns);
    }

    /**
     * Get Translatable Columns
     *
     * @return array
     */
    public function getTranslatableColumns()
    {
        $transColumns = array();
        foreach($this->columns as $c => $t) {
            if ($t) $transColumns[] = $c;
        }

        return $transColumns;
    }
}
