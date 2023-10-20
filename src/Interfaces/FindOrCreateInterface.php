<?php

namespace App\Interfaces;

interface FindOrCreateInterface
{
    public function findOrCreate(array $data);
}