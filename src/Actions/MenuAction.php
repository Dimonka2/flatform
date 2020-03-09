<?php
namespace dimonka2\flatform\Actions;

use dimonka2\flatform\Actions\Action;

abstract class MenuAction extends Action implements MenuContract
{
    public function getTitle()
    {
        return $this::title ?? '';
    }

    public function getIcon()
    {
        return $this::icon ?? '';
    }

    public function getName()
    {
        return $this::name ?? '';
    }

    public function getMenuItem($type): array
    {
        return [$type,
            'action' => $this,
            'title' => $this->getTitle(),
            'icon' => $this->getIcon()
        ];
    }
}
