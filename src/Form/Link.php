<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;

class Link extends ElementContainer
{
    protected $href;
    protected $post;
    protected $badge;
    protected $badgeColor;
    protected $badgePill;
    public $btn_group;
    public $title;
    public $icon;
    public $form_class;
    public $items_in_title = true;
    public $group;

    protected function is_post()
    {
        return !!$this->post;
    }

    protected function is_link()
    {
        return ($this->href !== null) && !$this->is_post();
    }

    protected function read(array $element)
    {
        $this->readSettings($element,
            ['href', 'post', 'title', 'icon', 'form-class', 'group',
            'badge', 'badgeColor', 'badgePill', ]);
        parent::read($element);
    }

    public function getOptions(array $keys)
    {
        $options = parent::getOptions($keys);
        if($this->is_link()) $options['href'] = $this->href;
        if($this->is_post()) $options['type'] = 'submit';
        return $options;
    }

    public function getTitle()
    {
        return (!is_null($this->icon) ?
            $this->createElement([
                'type' => 'i',
                'class' => $this->icon
                ])->render() . ' ' : '') .
            $this->title .
            $this->renderBadge().
            ($this->items_in_title ? $this->renderItems() : '');
    }

    public function renderBadge()
    {
        return ($this->badge ? ' '. $this->createElement([
                'type' => 'badge',
                'text' => $this->badge,
                'color' => $this->badgeColor,
                'pill' => $this->badgePill,
            ])->renderElement() : '');
    }

    protected function renderForm()
    {
        // render form
        $form = $this->createTemplate('link-form');
        if($this->group) $form->addClass('btn-group');
        $method = 'POST';
        if(is_array($this->post)) {
            foreach($this->post as $key => $item) {
                if($item == 'post') {
                    $method = $item;
                } else {
                    $form->elements[] = $this->createElement([
                        'type' => 'hidden',
                        'name' => $key,
                        'value' => $item,
                    ]);
                }
            }
        } else {
            $method = $this->post;
        }
        $form->read([
            'method' => $method,
            'url' => $this->href,
            'class' => $this->form_class,
            'template' => false,]);
        // debug($form);
        return $form->renderForm( $this->renderLink() );

    }

    protected function renderLink()
    {
        return $this->context->renderElement($this, $this->getTitle());
    }

    protected function render()
    {
        if($this->is_post()) {
            return $this->renderForm();
        } else  {
            return $this->renderLink();
        }
    }

    public function getTag()
    {
       return $this->is_link() ? 'a' : 'button';
    }

    /**
     * Get the value of href
     */
    public function getHref()
    {
        return $this->href;
    }


    /**
     * Get the value of badge_color
     */
    public function getBadgeColor()
    {
        return $this->badgeColor;
    }

    /**
     * Get the value of badge
     */
    public function getBadge()
    {
        return $this->badge;
    }
}
