<?php

namespace App\Indexer;

interface IndexerInterface
{
    public function getName(): string;

    public function addOne(string $typeName, $object): bool;

    public function deleteOne(string $typeName, string $uuid): bool;

    public function updateOne(string $typeName, $object): bool;

    public function addOrUpdateOne(string $typeName, $object): bool;
}
