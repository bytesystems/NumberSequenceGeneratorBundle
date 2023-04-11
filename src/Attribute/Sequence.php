<?php


namespace Bytesystems\NumberGeneratorBundle\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Sequence
{
    public function __construct(
        public string $key,
        public ?string $segment = null,
        public ?array $segments = [],
        public ?string $pattern = null,
        public ?int $init = null
    )
    {
    }
}