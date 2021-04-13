## Annotation
`use Bytesystems\NumberGeneratorBundle\Annotation as NG;`

Annotate a property of your Entity with
`@NG\Sequence(key="key",segment="segment",init=1000,pattern="IV{Y}-{#|6|y})`

This will set the annotated property to a sequencial number on persist.
The resulting number will follow the pattern, in this case a generated number might look like:
*IV2021-004121*

- *string key*, mandatory, defines the key for this sequence, multiple entities can share this key.
- *string segment*, optional, can be provided as static key or as another property of the annotated entity. Use curly braces to define the property.
- *string pattern*, optional, define a pattern for the sequence, see Pattern
- *int init*, optional, defines the inital number for the sequence, the first generated number is initial number + 1. Default value is 0.

## Sequences
Sequences will be generated and persisted automatically for key=*key* and segment=*null*.
It is up to you, to define segmented sequences by yourself and persist them.

## Segmentation
It is possible to segment number sequences for an entity based on another property of the entity.
```
/** 
* @ORM\Table()
* @ORM\Entity()
*/
class Foo
{
  private $thud = "thudValue";

  /**
  * @ORM\Column(type="integer")
  * @ORM\Id()
  * @ORM\GeneratedValue(strategy="AUTO")
  *
  * @var integer
  */
  private $id;

  /**
   * @ORM\Column(type="string", nullable=true)
   * @NG\Sequence(key="foo",segment="{thud}")
   * @var string
   */
  private $foo;
}
```
In the above example, the number sequence would be segmented by the property $thud.

## Pattern
The *{#}* token is mandatory and will be assumend, if pattern is omitted.
The following tokens are available, date components follow php datetime.format.

- *{#|L|C}* The current number of the sequence. 
  If L is provided, the number will be left-padded with zeroes
  If C, the date context, is provided, the number will reset on change of context.
  Allowed values for context are (y = year, m = month, d = day, W = week)
- *{D}* Datetime part following datetime.format, allowed values for D are almost all values that produce a fixed length, f.e. d (day of month, 2 digits with leading zero) is allowed, while j (day of month, without leading zeroes) is not.
  Allowed values:
  - d, day, 2 digits with leading zero
  - D, day, textual 3 letters
  - m, month, 2 digits with leading zero
  - M, month, textual 3 letters
  - y, year, 2 digits
  - Y, year, 4 digits
  - H, hours, 24-hour format, 2 digits with leading zero
  - w, week of year, 2 digits with leading zero (*changed to lowercase for consistency*)