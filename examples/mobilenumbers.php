<?php
use ReverseRegex\Lexer;
use ReverseRegex\Parser;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Random\MersenneRandom;

# require composer
require '../vendor/autoload.php';

# parse the regex
$lexer = new Lexer("6104([01]\d{3}|(2[1-9]|3[0-57-9]|4[7-9]|5[0-35-9]|6[679]|7[078]|8[178]|9[7-9])\d{2}|(20[2-9]|444|68[3-9]|79[01]|820|901)\d|(200[01]|2010|8984))\d{4}");
$parser    = new Parser($lexer,new Scope(),new Scope());
$generator = $parser->parse()->getResult();

# run the generator
$random = new MersenneRandom(777);

for($i = 50; $i > 0; $i--) {
    $result = '';
    $generator->generate($result,$random);    
    
    echo $result;
    echo PHP_EOL;
}