<?php


namespace Bytesystems\NumberGeneratorBundle\Annotation;

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
 *    @Attribute("pattern", required=false, type="string"),
 *    @Attribute("init", required=false, type="int")
 * })
 */
class Sequence
{
    /** @Required */
    public $key;
    public $segment;
    public $pattern;
    public $init;
}