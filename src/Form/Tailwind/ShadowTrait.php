<?php

namespace dimonka2\flatform\Form\Tailwind;

trait ShadowTrait
{
    protected $shadow;

    protected function readShadowTrait(array $element)
    {
        $this->shadow = $this->readSingleSetting($element, 'shadow');
    }

    protected function getAttributesShadowTrait($attributes)
    {
        if($this->shadow) {
            $shadow = $this->shadow;
            if(in_array( $shadow, ['xs', 'sm', 'md', 'lg', 'xl', '2xl'], true)){
                $shadow = 'shadow-' . $shadow;
            } else {
                $shadow = 'shadow';
            }
            $attributes['class'] = ($attributes['class'] ?? '') . ' ' . $shadow; 
        }
        return $attributes;
    }

}