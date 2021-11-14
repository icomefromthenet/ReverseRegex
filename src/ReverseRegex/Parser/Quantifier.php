<?php

declare(strict_types=1);

namespace ReverseRegex\Parser;

use ReverseRegex\Exception as ParserException;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Lexer;

/**
 *  Parse a group quantifer e.g (abghb){1,5} , (abghb){5} , (abghb)* , (abghb)? , (abghb)+.
 *
 *  @author Lewis Dyer <getintouch@icomefromthenet.com>
 *
 *  @since 0.0.1
 */
class Quantifier implements StrategyInterface
{
    /**
     *  Parse the current token for new Quantifiers.
     *
     *  @param ReverseRegex\Generator\Scope $head
     *  @param ReverseRegex\Generator\Scope $set
     *  @param ReverseRegex\Lexer $lexer
     *
     *  @return ReverseRegex\Generator\Scope a new head
     */
    public function parse(Scope $head, Scope $set, Lexer $lexer)
    {
        switch (true) {
            case $lexer->isNextToken(Lexer::T_QUANTIFIER_PLUS):
                $head = $this->quantifyPlus($head, $set, $lexer);
            break;
            case $lexer->isNextToken(Lexer::T_QUANTIFIER_QUESTION):
                $head = $this->quantifyQuestion($head, $set, $lexer);
            break;
            case $lexer->isNextToken(Lexer::T_QUANTIFIER_STAR):
                $head = $this->quantifyStar($head, $set, $lexer);
            break;
             case $lexer->isNextToken(Lexer::T_QUANTIFIER_OPEN):
                $head = $this->quantifyClosure($head, $set, $lexer);
            break;
            default:
                //do nothing no token matches found
        }

        return $head;
    }

    /**
     *  Parse the current token for + quantifiers.
     *
     *  @param ReverseRegex\Generator\Scope $head
     *  @param ReverseRegex\Generator\Scope $result
     *  @param ReverseRegex\Lexer $lexer
     *
     *  @return ReverseRegex\Generator\Scope a new head
     */
    public function quantifyPlus(Scope $head, Scope $result, Lexer $lexer)
    {
        $min = 1;
        $max = PHP_INT_MAX;

        $head->setMaxOccurances($max);
        $head->setMinOccurances($min);

        return $head;
    }

    /**
     *  Parse the current token for * quantifiers.
     *
     *  @param ReverseRegex\Generator\Scope $head
     *  @param ReverseRegex\Generator\Scope $result
     *  @param ReverseRegex\Lexer $lexer
     *
     *  @return ReverseRegex\Generator\Scope a new head
     */
    public function quantifyStar(Scope $head, Scope $result, Lexer $lexer)
    {
        $min = 0;
        $max = PHP_INT_MAX;

        $head->setMaxOccurances($max);
        $head->setMinOccurances($min);

        return $head;
    }

    /**
     *  Parse the current token for ? quantifiers.
     *
     *  @param ReverseRegex\Generator\Scope $head
     *  @param ReverseRegex\Generator\Scope $result
     *  @param ReverseRegex\Lexer $lexer
     *
     *  @return ReverseRegex\Generator\Scope a new head
     */
    public function quantifyQuestion(Scope $head, Scope $result, Lexer $lexer)
    {
        $min = 0;
        $max = 1;

        $head->setMaxOccurances($max);
        $head->setMinOccurances($min);

        return $head;
    }

    /**
     *  Parse the current token for closers : {###} { ## } {##,##}.
     *
     *  @param ReverseRegex\Generator\Scope $head
     *  @param ReverseRegex\Generator\Scope $result
     *  @param ReverseRegex\Lexer $lexer
     *
     *  @return ReverseRegex\Generator\Scope a new head
     */
    public function quantifyClosure(Scope $head, Scope $result, Lexer $lexer)
    {
        $tokens = [];
        $min = $head->getMinOccurances();
        $max = $head->getMaxOccurances();

        // move to the first token inside the quantifer.
        // parse for the minimum , move lookahead until read end of the closure or the `,`
        while (true === $lexer->moveNext() && !$lexer->isNextToken(Lexer::T_QUANTIFIER_CLOSE) && ',' !== $lexer->lookahead['value']) {
            if ($lexer->isNextToken(Lexer::T_QUANTIFIER_OPEN)) {
                throw new ParserException('Nesting Quantifiers is not allowed');
            }
            $tokens[] = $lexer->lookahead;
        }

        $min = $this->convertInteger($tokens);

        // do we have a maximum after the comma?
        if (',' === $lexer->lookahead['value']) {

            // make sure we have values to gather ie not {778,}
            $tokens = [];

            // move to the first token after the `,` character
            // grab the remaining numbers
            while ($lexer->moveNext() && !$lexer->isNextToken(Lexer::T_QUANTIFIER_CLOSE)) {
                if ($lexer->isNextToken(Lexer::T_QUANTIFIER_OPEN)) {
                    throw new ParserException('Nesting Quantifiers is not allowed');
                }

                $tokens[] = $lexer->lookahead;
            }

            $max = $this->convertInteger($tokens);
        } else {
            $max = $min;
        }

        $head->setMaxOccurances($max);
        $head->setMinOccurances($min);

        // skip the lexer to the closing token
        $lexer->skipUntil(Lexer::T_QUANTIFIER_CLOSE);

        // check if the last matched token was the closing bracket
        // not going to stop errors like {#####,###{[a-z]} {#####{[a-z]}
        if (!$lexer->isNextToken(Lexer::T_QUANTIFIER_CLOSE)) {
            throw new ParserException('Closing quantifier token `}` not found');
        }

        return $head;
    }

    /**
     *  Convert a collection of Lexer::T_LITERAL_NUMERIC tokens into integer.
     *
     *  @param array $tokens collection of tokens from lexer
     *
     *  @return int the size
     */
    protected function convertInteger(array $tokens)
    {
        $number_string = array_map(function ($item) { return $item['value']; }, $tokens);
        $number_string = trim(implode('', $number_string));

        $value = preg_match('/^(0|(-{0,1}[1-9]\d*))$/', $number_string);

        if (0 == $value) {
            throw new ParserException('Quantifier expects and integer compitable string');
        }

        return intval($number_string);
    }
}
