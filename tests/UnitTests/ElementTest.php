<?php
namespace Tests\UnitTests;

use PHPUnit\Framework\TestCase;
use dimonka2\flatform\Form\Context;
use dimonka2\flatform\Form\Element;
use dimonka2\flatform\FlatformService;
use dimonka2\flatform\Form\Contracts\IElement;
use InfyOm\Generator\Utils\ResponseUtil;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Config\Repository as ConfigRepository;

class ElementTest extends TestCase
{
    public function setUp(): void
    {
        app()->bind('config', ConfigRepository::class);
        app()->bind('flatform', FlatformService::class);
        config(['flatform.test' => 1]);
    }

    public function test_basic_rendering()
    {
        // empty element
        $context = new Context();
        $element = new Element([], $context);
        $this->assertEquals('<div />', $element->renderElement());

        // add some text
        $element->setText('test');
        $this->assertEquals('<div>test</div>', $element->renderElement());
        //echo($element->renderElement());

        // init element with text
        $element = new Element(['text' => 'test'], $context);
        $this->assertEquals('<div>test</div>', $element->renderElement());

        // init element with class
        $element = new Element(['class' => 'test'], $context);
        $this->assertEquals('<div class="test" />', $element->renderElement());

        // init element with style
        $element = new Element(['style' => 'width:0;'], $context);
        $this->assertEquals('<div style="width:0;" />', $element->renderElement());

        // set element id
        $element = new Element(['id' => 'test'], $context);
        $this->assertEquals('<div id="test" />', $element->renderElement());

        $this->assertEquals('<div />', FlatformService::render([[]]));
        $this->assertEquals('<div>test</div>', FlatformService::render([['text' => 'test']]));

    }

    public function test_element_properties()
    {
        $context = new Context();
        $element = new Element([], $context);
        $element->setText('test');
        $this->assertEquals('test', $element->getText() );

        $element->setHidden(1);
        $this->assertEquals(1, $element->getHidden() );
        // check if nothing is rendered
        $this->assertEquals('', $element->renderElement() );
        $element->setHidden(0);
        $this->assertEquals(0, $element->getHidden() );
        $element->setAttribute('wire:click', 'onClick');
        $this->assertEquals('<div wire:click="onClick">test</div>', $element->renderElement() );

        // check on render
        $element->setOnRender(function(){
            return 'test';
        });
        $this->assertEquals('test', $element->renderElement() );

        // test onLoad
        $element = new Element(['onLoaded' => function(IElement $el) {
            $el->class = 'test';
        }], $context);
        $this->assertEquals('<div class="test" />', $element->renderElement());

        $element->addClass('plus');
        $this->assertEquals('<div class="test plus" />', $element->renderElement());
    }

}
