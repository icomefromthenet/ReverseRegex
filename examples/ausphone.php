<?php
use ReverseRegex\Lexer;
use ReverseRegex\Parser;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Random\MersenneRandom;

# require composer
require '../vendor/autoload.php';

# parse the regex
$lexer     = new Lexer('\(0[23478]\) 9[0-9]{3}-[0-9]{4}');
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