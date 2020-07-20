<?php

namespace PGNChess\Tests\Unit\ML\Supervised\Regression\Labeller;

use PGNChess\Board;
use PGNChess\ML\Supervised\Regression\Labeller\Primes as PrimesLabeller;
use PGNChess\PGN\Symbol;
use PGNChess\Tests\AbstractUnitTestCase;
use PGNChess\Tests\Sample\Opening\Sicilian\Open as OpenSicilian;

class PrimesTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function open_sicilian()
    {
        $board = (new OpenSicilian(new Board))->play();

        $expected = [
            Symbol::WHITE => 549.66,
            Symbol::BLACK => 554.66,
        ];

        $this->assertEquals($expected, (new PrimesLabeller($board))->calc());
    }
}