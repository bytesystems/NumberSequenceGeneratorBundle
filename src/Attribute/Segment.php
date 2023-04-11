<?php

namespace Bytesystems\NumberGeneratorBundle\Attribute;


class Segment
{
    public function __construct(
        public ?string $value = null,
        public ?string $pattern = null
    )
    {
    }
}