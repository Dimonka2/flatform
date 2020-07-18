<?php
namespace Tests\UnitTests;

use PHPUnit\Framework\TestCase;
use dimonka2\flatform\Form\Context;
use dimonka2\flatform\FlatformService;
use dimonka2\flatform\Form\ElementContainer;
use Illuminate\Config\Repository as ConfigRepository;

class ElementContainerTest extends TestCase
{
    public function setUp(): void
    {
        app()->bind('config', ConfigRepository::class);
        app()->bind('flatform', FlatformService::class);
        // app()->bind('session', SessionManager::class);
        // class_alias(SessionManager::class, 'session');
        config(['flatform.test' => 1]);
    }

    public function test_basic_rendering()
    {
        $context = new Context();
        $element = new ElementContainer([], $context);
        $this->assertEquals('<div />', $element->renderElement());

        // test element with sub elements declared as 'items'
        $element = new ElementContainer(['items'=>[['div']]], $context);
        $this->assertEquals('<div><div /></div>', $element->renderElement());

        // test element with sub elements declared as array
        $element = $context->createElement([[['div']]]);
        $this->assertEquals('<div><div /></div>', $element->renderElement());

        // test element with 2 sub elements declared as array
        $element = $context->createElement([[['div'], ['div']]]);
        $this->assertEquals('<div><div /><div /></div>', $element->renderElement());

        // test element with 2 sub elements declared as array and text
        $element = $context->createElement(['text' => 'test', [['span'], ['div']]]);
        $this->assertEquals('<div>test<span /><div /></div>', $element->renderElement());

        // test element with 2 sub elements declared as array and text and extra text element
        $element = $context->createElement(['text' => 'test', [['span'], ['div'], ['_text', 'text' => 'test']]]);
        $this->assertEquals('<div>test<span /><div />test</div>', $element->renderElement());

    }

    public function test_fluent_functions()
    {
        $context = new Context();

        // test element with sub elements declared as array
        $element = new ElementContainer([], $context);
        $this->assertEquals('<div />', $element->renderElement());

        $element->addTextElement('test');
        $this->assertEquals('<div>test</div>', $element->renderElement());
        $element->setText('test1');
        $this->assertEquals('<div>test1test</div>', $element->renderElement());
        $element->addTextElement('test');
        $this->assertEquals('<div>test1testtest</div>', $element->renderElement());
        $this->assertEquals(2, count($element));

        // read items
        $element->readItems([['_text', 'text' => '123']]);
        $this->assertEquals('<div>test1testtest123</div>', $element->renderElement());
        $this->assertEquals(3, count($element));

        $element->readItems([['_text', 'text' => '456']], true);
        $this->assertEquals('<div>test1456</div>', $element->renderElement());
        $this->assertEquals(1, count($element));

        // check iterator
        foreach($element as $subelement){
            $this->assertEquals('_text', $subelement->getTag());
        }


    }

}
