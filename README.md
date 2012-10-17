ReverseRegex
============

Use Regular Expressions to generate text string in PHP.

To install use composer

```json
{
  "require" : {
		"icomefromthenet/reverse-regex" : "dev-master"
	}
}
```

#Example

```php

use ReverseRegex\Lexer;
use PHPStats\Generator\SimpleRandom;
use ReverseRegex\Parser;
use ReverseRegex\Generator\Scope;

# load composer
require "vendor/autoload.php";

$lexer = new  Lexer('[a-z]{10}');
$gen   = new SimpleRandom(10007);
$result = '';

$parser = new Parser($lexer,new Scope(),new Scope());
$parser->parse()->getResult()->generate($result,$gen);

echo $result;

```

# Regex Support

<table>
 <tr>
  <th>
    Example
  </th>
  <th>
    Description
  </th>
  <th>
    Resulting String
  </th>
 </tr>
 
 <tr>
  <td> (abcf) </td> <td> Support literals this would generate string </td> <td>`abcf`</td>  
 </tr>
 <tr>
   <td> \((abcf)\) </td> <td> Escape meta characters as you normally would in a regex </td> <td>`(abcf)`</td>  
 </tr>
 <tr>
  <td> [a-z] </td> <td> Character Classes are supported </td> <td>`a`</td>  
 </tr>
 <tr>
  <td> a{5} </td> <td> Quantifiers supported always <strong>last</strong> group or literal or character class </td> <td>`aaaaa`</td>  
 </tr>
 <tr>
  <td> a{1-5} </td> <td> Range Quantifiers supported</td> <td>`aa`</td>  
 </tr>
 <tr>
  <td> a|b|c </td> <td> Alternation supported pick one of three at random </td> <td>`b`</td>  
 </tr>
 <tr>
  <td> a|(y|d){5} </td> <td> Groups supported with alternation and quantifiers </td> <td>`ddddd`</td>  
 </tr>
 <tr>
  <tr>
    <td> \d </td> <td> Digit shorthand equ [0-9]  </td> <td>`1`</td>  
  </tr>
  <tr>
    <td> \w </td><td> word character shorthand equ [a-zA-Z0-9_]  </td> <td>`j`</td>  
  </tr>
  <tr>
    <td> \W </td><td>Non word character shorthand equ [^a-zA-Z0-9_]  </td> <td>`j`</td>  
  </tr>
  <tr>
    <td> \s </td><td>White space shorthand ASCII only </td> <td>` `</td>  
  </tr>
  <tr>
    <td> \S </td><td>Non White space shorthand ASCII only </td> <td>`i`</td>  
  </tr>
  <tr>
    <td> . </td><td>Dot all ASCII characters </td> <td>`$`</td>  
  </tr>
  <tr>
    <td> * + ? </td><td>Short hand quantifiers, recommend not use them </td> <td> </td>  
  </tr>
  <tr>
    <td> \X{00FF}[\X{00FF}-\X{00FF}] </td><td>Unicode ranges</td> <td> </td>  
  </tr>
  <tr>
    <td> \xFF[\xFF-\xFF] </td><td> Hex ranges</td> <td> </td>  
  </tr>
 </table>

## Real Examples




