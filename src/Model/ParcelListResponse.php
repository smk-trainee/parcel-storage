<?php

declare(strict_types=1);

namespace App\Model;

class ParcelListResponse
{
    /**
     * @var ParcelListItem[]
     */
    private array $items;

    /**
     * @return ParcelListItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param ParcelListItem[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }
}
