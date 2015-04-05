<?php

namespace Opsway\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode,
    Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class Contains extends FunctionNode
{
    private $expr1;
    private $expr2;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->expr1 = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf(
            '(%s @> %s)',
            $this->expr1->dispatch($sqlWalker),
            $sqlWalker->walkInputParameter($this->expr2)
        );
    }
}