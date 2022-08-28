<?php


namespace Bytesystems\NumberGeneratorBundle\Annotation;

use Attribute;

/**
 * Annotation to flag an entity field to be automatically filled
 * with a sequence number
 *
 * @Annotation()
 * @Annotation\Target({"PROPERTY"})
 *
 * @Attributes({
 *    @Attribute("key", required=true, type="string"),
 *    @Attribute("segment", required=false, type="string"),
 *    @Attribute("segments", required=false, type="array"),
 *    @Attribute("pattern", required=false, type="string"),
 *    @Attribute("init", required=false, type="int")
 * })
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Sequence
{
    /** @Required
     * @var string
     */
    public $key;
    /** @var string */
    public $segment;

    /** @var array  */
    public $segments;

    /** @var string */
    public $pattern;
    /** @var int */
    public $init;
}