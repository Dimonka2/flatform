<?php
namespace Tests\UnitTests;

use PHPUnit\Framework\TestCase;
use dimonka2\flatform\Form\Context;
use dimonka2\flatform\FlatformService;
use Illuminate\Config\Repository as ConfigRepository;

class ContextTest extends TestCase
{
    public function setUp(): void
    {
        app()->bind('config', ConfigRepository::class);
        app()->bind('flatform', FlatformService::class);
        config(['flatform.test' => 1]);
    }

    public function test_basic_functions()
    {
        $context = new Context();
        $element = $context->createElement([]);
        $this->assertEquals('<div />', $element->renderElement());

        $definition = $context->ensureType([], 'text');
        $this->assertEquals('text', $definition['type'] ?? null);

        $definition = $context->ensureType($definition, 'i');
        $this->assertEquals('i', $definition['type'] ?? null);

        $element = $context->make('span', ['id' => 'test']);
        $this->assertEquals('<span id="test" />', $element->renderElement());

        $element = $context->make('dropdown', ['items' => ['dd-item', 'title' => 'test']]);
        $this->assertEquals('<span id="test" />', $element->renderElement());

    }
}
