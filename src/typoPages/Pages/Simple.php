<?php
namespace typoPages\Pages;

use yimaWidgetator\Widget\AbstractWidget;

class Simple extends AbstractWidget
{
    /**
     * Render widget as string output
     *
     * @return string
     */
    public function render()
    {
        return '<p>This is <strong>Simple</strong> Widget.</p>';
    }
}