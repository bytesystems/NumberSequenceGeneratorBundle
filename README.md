# BytesystemsNumberGeneratorBundle
*This bundle provides an annotation driven approach to generate number sequences for various use-cases, for example for invoices, offers, orders etc.*
For now it depends on doctrine\orm to store the sequences and doctrine\migrations to prepare your database. 

## Installation
`$ composer require bytesystems/number-sequence-generator`\
Prepare your database:\
`$ symfony console doctrine:migrations:diff`\
`$ symfony console doctrine:migrations:migrate`

If you don't want to add 

No further configuration for the bundle is needed.\
To configure your sequences, use **Annotations**

## Usage
To create a number sequence for an entity, annotate the property, that should be set to the generated number.

### Annotation
Add the following use statement to your entity\
`use Bytesystems\NumberGeneratorBundle\Annotation as NG;`  
and annotate the property with:\
`@NG\Sequence(key="key",segment="segment",init=1000,pattern="IV{Y}-{#|6|y})`

The resulting number will follow the pattern, in this case a generated number might look like:
*IV2021-004121*

#### *string key*
**mandatory**\
Sequences will be generated and persisted automatically for the set key.
#### *string segment*
*optional*\
Offers the possibility to divide a sequence in segments. The segment uses the same values for key, pattern and init, but has its own counter.
The segment can be provided as static string or as reference to another property of the entity.
To define a reference use `segment="{property}"`.
#### *string pattern*
*optional, defaults to {#}*\
The *{#}* token is mandatory and will be assumed, if pattern is omitted.
The following tokens are available, date components follow PHP's datetime.format.

- *{#|L|C}* The current number of the sequence.
  If L is provided, the number will be left-padded with zeroes to length L.\
  If C - the date context - is provided, the number will reset on change of context.\
  Allowed values for context are (y = year, m = month, d = day, w = week and h = hour).
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

#### *int init*
*optional, defaults to 0*\
Defines the inital number for the sequence, the first generated number is initial number + 1.

## About Segmentation
It is up to you, to define segmented sequences by yourself and persist them.
This gives you flexibility to define dynamic sequences, that rely on another property from your entity.\
It falls back to default sequence unless you explicitly define the sequence manually.

Imagine the entity Product in your application. You want all products to have a number like:\
`ACME-5123141-P`\
But products from supplier Olymp should have their own article numbers like:\
`ZEUS-41313-P`\
The annotation achieving your goal would look like:\
`Sequence(key="product",segment="{supplierId}",init=1000,pattern="ACME-{#|7}-P")`\

The resulting entity:
```
/** 
* @ORM\Table()
* @ORM\Entity()
*/
class Product
{
  /**
  * @ORM\Column(type="integer")
  * @ORM\Id()
  * @ORM\GeneratedValue(strategy="AUTO")
  *
  * @var integer
  */
  private $id;

  /**
   * @ORM\Column(type="int")
   * @var int
   */
  private supplierId;

  /**
   * @ORM\Column(type="string", nullable=true)
   * @NG\Sequence(key="product",segment="{supplierId}",init=1000,pattern="ACME-{#|7}-P")
   * @var string
   */
  private productNo;
  
  ...
}
```
All products will have number like `ACME-5123141-P`\
Assuming the supplierId of Olymp is 42, 
enable the Olymp sequence using the following SQL statement:\
`insert into bytesystems_number_sequence (sequence, segment, pattern, current_number, updated_at) values ('product','42', 'ZEUS-{#|5}-P',0,now());` 

## ToDo
- include console command to generate a sequence
- include option to auto enable segmented sequences

## License
[The MIT License (MIT)](LICENSE)
