<?php
use ReverseRegex\Lexer;
use ReverseRegex\Parser;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Random\MersenneRandom;

# require composer
require '../vendor/autoload.php';

# parse the regex
$lexer = new Lexer("(0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2})");
$parser    = new Parser($lexer,new Scope(),new Scope());
$generator = $parser->parse()->getResult();

# run the generator
$random = new MersenneRandom(777);

for($i = 20; $i > 0; $i--) {
    $result = '';
    $generator->generate($result,$random);    
    
    echo $result;
    echo PHP_EOL;
}