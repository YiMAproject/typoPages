<?php
namespace typoPages\Pages;

/**
 * Class Simple
 *
 * @package typoPages\Pages
 */
class Simple extends PageAbstract
{
    /**
     * @var string Content
     */
    protected $content;

    /**
     * @var string Style
     */
    protected $style = 'default';

    /**
     * Render widget as string output
     *
     * @return string
     */
    public function render()
    {
        return $this->getContent().' With '.$this->getStyle().' Style.';
    }

    /**
     * Set Content
     *
     * @param string $content Content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = (string) $content;

        return $this;
    }

    /**
     * Get Content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @param string $style
     */
    public function setStyle($style)
    {
        $this->style = $style;
    }

}
