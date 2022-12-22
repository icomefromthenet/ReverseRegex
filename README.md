ReverseRegex
============

[![PHP unit](https://github.com/ilario-pierbattista/ReverseRegex/actions/workflows/ci.yaml/badge.svg?branch=master)](https://github.com/ilario-pierbattista/ReverseRegex/actions/workflows/ci.yaml)

> This is a fork of https://github.com/icomefromthenet/ReverseRegex (that provides `icomefromthenet/reverse-regex`).

Use Regular Expressions to generate text strings can be used in the following situations:

1. Wrting test data for web forms.
2. Writing test data for databases.
3. Generating test data for regular expressions. 


##Example

```php

use ReverseRegex\Lexer;
use ReverseRegex\Random\SimpleRandom;
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

***Produces***

```
jmceohykoa
aclohnotga
jqegzuklcv
ixdbpbgpkl
kcyrxqqfyw
jcxsjrtrqb
kvaczmawlz
itwrowxfxh
auinmymonl
dujyzuhoag
vaygybwkfm
```
#### Other examples

1. [Australian phone numbers](https://github.com/ilario-pierbattista/ReverseRegex/blob/master/examples/ausphone.php)
2. [Australian postcodes](https://github.com/ilario-pierbattista/ReverseRegex/blob/master/examples/auspostcode.php)
3. [Mobile numbers](https://github.com/ilario-pierbattista/ReverseRegex/blob/master/examples/mobilenumbers.php)


##Installing

To install use composer

    composer require ilario-pierbattista/reverse-regex

## Writing a Regex

1. Escape all meta-characters i.e. if you need to escape the character in a regex you will need to escape here.
2. Not all meta-characters are suppported see list below.
3. Use `\X{####}` to specify unicode value use `[\X{####}-\X{####}]` to specify range.
4. Unicdoe `\p` not supported, I could not find a port of [UCD](http://www.unicode.org/ucd/) to php, maybe in the future support be added.
5. Quantifiers are applied to left most group, literal or character class.
6. Beware of the `+` and `*` quantifers they apply a possible maxium number of occurances up to `PHP_INT_MAX`.

### Regex Support

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
  <td> a{1,5} </td> <td> Range Quantifiers supported</td> <td>`aa`</td>  
 </tr>
 <tr>
  <td> a|b|c </td> <td> Alternation supported pick one of three at random </td> <td>`b`</td>  
 </tr>
 <tr>
  <td> a|(y|d){5} </td> <td> Groups supported with alternation and quantifiers </td> <td>`ddddd` or `a` or `yyyyy` </td>  
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



