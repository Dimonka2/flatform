<?php
namespace dimonka2\flatform\Actions;

interface MenuContract
{
    public function getTitle();
    public function getIcon();
    public function getName();
    public function getMenuItem($type): array;
}
