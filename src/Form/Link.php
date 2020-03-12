<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Actions\Action;
use dimonka2\flatform\Form\ElementContainer;

class Link extends ElementContainer
{
    protected $href;
    protected $post;
    protected $badge;
    protected $badgeColor;
    protected $badgePill;
    protected $confirm; // show a modal dialog to confirm the action
    protected $action;  // link to action
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
            ['href', 'post', 'title', 'icon', 'form-class', 'group', 'confirm',
            'badge', 'badgeColor', 'badgePill', 'action']);
        parent::read($element);
        if(is_array($this->badge)) $this->badge = $this->createElement($this->badge, 'badge');
    }

    public function getOptions(array $keys)
    {
        $options = parent::getOptions($keys);
        if($this->action) {
            $options['onclick'] = Action::formatClick($this->action);
        } else {
            if($this->is_link()) $options['href'] = $this->href;
            if($this->is_post()) $options['type'] = 'submit';
        }
        return $options;
    }

    protected function renderTitle()
    {
        if($this->title){
            if(is_object($this->title) || is_array($this->title)) {
                return $this->renderItem($this->title);
            } else return $this->title;
        } else return;
    }

    public function getTitle()
    {
        return (!is_null($this->icon) ?
            $this->createElement(['class' => $this->icon], 'i')->render() . ' ' : '') .
            $this->renderTitle() .
            $this->renderBadge().
            ($this->items_in_title ? $this->renderItems() : '');
    }

    public function renderBadge()
    {
        if($this->badge){
            if(is_object($this->badge)) {
                return ' ' . $this->badge->renderElement();
            } else {
                $badge = ['text' => $this->badge];
                if($this->badgeColor) $badge['color'] = $this->badgeColor;
                if($this->badgePill) $badge['pill'] = $this->badgeColor;
                return ' '. $this->createElement($badge, 'badge')->renderElement();
            }
        } else return;
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
                        'name' => $key,
                        'value' => $item,
                    ], 'hidden');
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
