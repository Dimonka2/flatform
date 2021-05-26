<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Actions\Action;
use dimonka2\flatform\Form\ElementContainer;

class Link extends ElementContainer
{
    protected $href; // url (string) or route (array)
    protected $post;
    protected $badge;
    protected $badgeColor;
    protected $badgeClass;
    protected $badgePill;
    protected $action;  // link to action
    protected $titleTemplate;
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

    public function getUrl()
    {
        if($this->action) return '#';
        if(is_string($this->href)) return $this->href;
        if (is_array($this->href)) {
            $len = count($this->href);
            switch ($len) {
                case 1: return route($this->href[0]);
                case 2: return route($this->href[0], $this->href[1]);
                case 3: return route($this->href[0], $this->href[1], $this->href[2]);
            }
        }
    }

    protected function read(array $element)
    {
        $this->readSettings($element,
            ['href', 'post', 'title', 'icon', 'form-class', 'group', 'titleTemplate',
            'badge', 'badgeColor', 'badgeClass', 'badgePill', 'action']);
        parent::read($element);
        if(is_array($this->badge)) {
            $this->badge = $this->createElement($this->badge, 'badge');
            if($this->badgeClass) $this->badge->addClass($this->badgeClass);
        }
    }

    public function getOptions(array $keys)
    {
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
        } else return;
    }

    public function getTitle()
    {
        if($this->titleTemplate){
            $html = $this->renderTitle();
            $template = $this->titleTemplate;
            if(!is_null($template) &&  $template != false) {
                foreach(explode(';', $template) as $template) {
                    $html = $this->context->renderView(
                        view($template)
                        ->with('element', $this)
                        ->with('html', $html)
                    );
                }
            }
            return $html;
        }

        return (!is_null($this->icon) ?
            $this->createElement(['class' => $this->icon], 'i')->render() . ' ' : '') .
            $this->renderTitle() .
            $this->text .
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
                if($this->badgeClass) $badge['+class'] = $this->badgeClass;
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
       if($this->type == 'a')  return 'a';
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
