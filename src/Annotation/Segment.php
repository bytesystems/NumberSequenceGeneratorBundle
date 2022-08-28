<?php

namespace Bytesystems\NumberGeneratorBundle\Annotation;

use Attribute;

/**
 *
 * @Annotation()
 *
 * @Attributes({
 *    @Attribute("value", required=true, type="string"),
 *    @Attribute("pattern", required=false, type="string"),
 * })
 */
#[Attribute()]

class Segment
{

    /** @Required
     * @var string
     */
    public $value;

    /** @Required
     * @var string
     */
    public $pattern;
}