<?php
namespace Tests\UnitTests;

use Tests\TestCase;
use dimonka2\flatform\Form\Context;

class InputTest extends TestCase
{
    public function test_basic_functions()
    {
        $context = new Context();

        // basic input
        $input = $context->make('text', ['template' => false, 'col' => false]);
        $this->assertEquals('<input type="text" />', $input->renderElement());

    }
}
