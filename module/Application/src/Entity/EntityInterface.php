<?php

declare(strict_types=1);

namespace Application\Entity;

interface EntityInterface
{
    public function exchangeArray(array $data);
    public function getArrayCopy();
    public function offsetGet($offset);
    public function offsetSet($offset, $value);
}