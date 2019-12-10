<?php
use dimonka2\flatform\Form\Context;

$context = new Context([[['type' => 'text', '_surround' => ['type' => 'div', 'class' => 'test', '_surround' => ['type' => 'div', 'id' => 1] ]]]]);
echo $context->render();
