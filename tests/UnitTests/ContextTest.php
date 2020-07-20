<?php
namespace Tests\UnitTests;

use Tests\TestCase;
use dimonka2\flatform\Flatform;
use dimonka2\flatform\Form\Context;
use dimonka2\flatform\Form\Navs\Dropdown;
use dimonka2\flatform\Form\Components\Table\Table;

class ContextTest extends TestCase
{
    public function test_basic_functions()
    {
        $context = new Context();
        $element = $context->createElement([]);
        $this->assertEquals('<div></div>', $element->renderElement());

        $definition = $context->ensureType([], 'text');
        $this->assertEquals('text', $definition['type'] ?? null);

        $definition = $context->ensureType($definition, 'i');
        $element = $context->createElement($definition);
        $this->assertEquals('i', $definition['type'] ?? null);
        $this->assertEquals('<i></i>', $element->renderElement());

        $element = $context->make('span', ['id' => 'test']);
        $this->assertEquals('<span id="test"></span>', $element->renderElement());

        // check some void tags
        $element = $context->make('br');
        $this->assertEquals('<br />', $element->renderElement());
        $element = $context->make('img');
        $this->assertEquals('<img />', $element->renderElement());
        $element = $context->make('input', ['template' => false]);
        $this->assertEquals('<input />', $element->renderElement());

        $dropdown = $context->createElement(['dropdown']);
        // var_dump($template);
        $this->assertIsObject($dropdown);
        $this->assertTrue($dropdown instanceof Dropdown);
        $html = $dropdown->renderElement();
        $this->assertStringContainsString('data-toggle="dropdown"', $html);
        $this->assertStringContainsString('dropdown-menu', $html);

        $element = $context->make('xtable');
        $this->assertIsObject($element);
        $this->assertTrue($element instanceof Table);
        $html = $element->renderElement();
        $this->assertStringContainsString('<table', $html);
        $this->assertStringContainsString('<thead>', $html);
        $this->assertStringContainsString('<tbody>', $html);
        $this->assertStringContainsString('No data available', $html);

        $context = Flatform::context();
        $this->assertEquals('<div></div>', $context->render([[]]));
    }
}
