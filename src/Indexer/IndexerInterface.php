<?php

namespace App\Indexer;

interface IndexerInterface
{
    public function getName(): string;

    public function addOne(string $typeName, $object): int;

    public function deleteOne(string $typeName, string $uuid): int;

    public function updateOne(string $typeName, $object): int;

    public function addOrUpdateOne(string $typeName, $object): int;
}
