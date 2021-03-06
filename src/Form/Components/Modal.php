<?php

namespace dimonka2\flatform\Form\Components;

use dimonka2\flatform\Form\ElementContainer;

class Modal extends ElementContainer
{
    protected $title;
    protected $footer;
    protected $size;
    protected $postion;
    protected $show;

    protected function read(array $element)
    {
        $this->readSettings($element, ['title', 'footer', 'position', 'size', 'show']);
        parent::read($element);
    }

    public function render()
    {
        $html = $this->text . $this->renderItems();

        return $html;
    }

    public function hasTitle()
    {
        return !!$this->title;
    }

    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->renderItem($this->title);
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of size
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set the value of size
     *
     * @return  self
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    public function hasFooter()
    {
        return !!$this->footer;
    }

    /**
     * Get the value of footer
     */
    public function getFooter()
    {
        return $this->renderItem($this->footer);
    }

    /**
     * Set the value of footer
     *
     * @return  self
     */
    public function setFooter($footer)
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * Get the value of postion
     */
    public function getPostion()
    {
        return $this->postion;
    }

    /**
     * Set the value of postion
     *
     * @return  self
     */
    public function setPostion($postion)
    {
        $this->postion = $postion;

        return $this;
    }

    /**
     * Get the value of show
     */
    public function getShow()
    {
        return $this->show;
    }

    /**
     * Set the value of show
     *
     * @return  self
     */
    public function setShow($show)
    {
        $this->show = $show;

        return $this;
    }
}
