<?php

namespace dimonka2\flatform\Form\Tailwind\Navs;

use dimonka2\flatform\Actions\Action;
use dimonka2\flatform\Traits\RouteTrait;
use dimonka2\flatform\Form\ElementContainer;

class Link extends ElementContainer
{
    use RouteTrait;

    protected $href; // url (string) or route (array)
    protected $post;
    protected $badge;
    protected $action;  // link to action
    protected $font_color = 'blue';
    public $title;
    public $icon;
    public $form_class;
    public $items_in_title = true;

    protected function is_post()
    {
        return !!$this->post;
    }

    protected function is_link()
    {
        return ($this->href !== null) && !$this->is_post();
    }

    public function getUrl()
    {
        if($this->action) return '#';
        if(is_string($this->href)) return $this->href;
        if (is_array($this->href)) return self::getRouteUrl($this->href);
    }

    protected function read(array $element)
    {
        $this->readSettings($element,
            ['href', 'post', 'title', 'icon', 
                'form-class', 'font-color',
                'badge', 'action']);
        parent::read($element);
        if(is_array($this->badge)) {
            $this->badge = $this->createElement($this->badge, 'badge');
        }
    }

    public function getOptions(array $keys)
    {
        if($this->font_color) $this->addClass("text-{$this->font_color}-700 hover:text-{$this->font_color}-500");
        $options = parent::getOptions($keys);
        if($this->action) {
            $options['onclick'] = Action::formatClick($this->action);
        } else {
            if($this->is_link()) $options['href'] = $this->getUrl();
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
        };
    }

    public function getTitle()
    {
        return 
            $this->renderIcon() . 
            $this->renderTitle() .
            $this->text .
            $this->renderBadge() .
            $this->renderLinkItems();
    }

    protected function renderIcon()
    {
        if(!$this->icon) return;
        return   $this->createElement(['class' => $this->icon], 'i')
            ->addClass('mr-2')->render();
    }

    protected function renderLinkItems()
    {
        if(!$this->items_in_title) return;
        return $this->renderItems();
    }

    protected function renderBadge()
    {
        if($this->badge){
            $badge = $this->badge;
            if(!is_object($badge)) {
                $badge = ['text' => $this->badge, '+class' => 'ml-2'];
                $badge = $this->createElement($badge, 'badge');
            }
            return $badge->renderElement();
        }
    }

    protected function renderForm()
    {
        // render form
        $form = $this->createTemplate('link-form');
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
        return $this->renderer()->renderElement($this, $this->getTitle());
    }

    public function render()
    {
        if($this->is_post()) {
            return $this->renderForm();
        } else  {
            return $this->renderLink();
        }
    }

    public function getTag()
    {
       if($this->is_link() && $this->type == 'a')  return 'a';
       return 'button';
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

    /**
     * Set the value of href
     *
     * @return  self
     */
    public function setHref($href)
    {
        $this->href = $href;

        return $this;
    }
}
