<?php

namespace Chess\Tests\Unit\ML\Supervised\Classification;

use Chess\Board;
use Chess\Combinatorics\RestrictedPermutationWithRepetition;
use Chess\HeuristicPicture;
use Chess\ML\Supervised\Classification\LinearCombinationLabeller;
use Chess\PGN\Convert;
use Chess\PGN\Symbol;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Tests\Sample\Checkmate\Fool as FoolCheckmate;
use Chess\Tests\Sample\Checkmate\Scholar as ScholarCheckmate;
use Chess\Tests\Sample\Opening\Benoni\BenkoGambit;
use Chess\Tests\Sample\Opening\Sicilian\Open as ClosedSicilian;

class LinearCombinationLabellerTest extends AbstractUnitTestCase
{
    static $permutations;

    public static function setUpBeforeClass(): void
    {
        $dimensions = (new HeuristicPicture(''))->getDimensions();

        self::$permutations = (new RestrictedPermutationWithRepetition())
            ->get(
                [ 8, 13, 21, 34],
                count($dimensions),
                100
            );
    }

    /**
     * @test
     */
    public function start_labelled()
    {
        $board = new Board();

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $end = end($balance);

        $label = (new LinearCombinationLabeller(self::$permutations))->label($end);

        $expected = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function w_e4_b_e5_labelled()
    {
        $board = new Board();
        $board->play(Convert::toStdObj(Symbol::WHITE, 'e4'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'e5'));

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $end = end($balance);

        $label = (new LinearCombinationLabeller(self::$permutations))->label($end);

        $expected = [
            Symbol::WHITE => 0,
            Symbol::BLACK => 0,
        ];

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function w_e4_b_Na6_labelled()
    {
        $board = new Board();
        $board->play(Convert::toStdObj(Symbol::WHITE, 'e4'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'Na6'));

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $end = end($balance);

        $expected = [
            Symbol::WHITE => 2,
            Symbol::BLACK => 26,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))->label($end);

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function w_e4_b_Nc6_labelled()
    {
        $board = new Board();
        $board->play(Convert::toStdObj(Symbol::WHITE, 'e4'));
        $board->play(Convert::toStdObj(Symbol::BLACK, 'Nc6'));

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $end = end($balance);

        $expected = [
            Symbol::WHITE => 2,
            Symbol::BLACK => 1,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))->label($end);

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function fool_checkmate_labelled()
    {
        $board = (new FoolCheckmate(new Board()))->play();

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $end = end($balance);

        $expected = [
            Symbol::WHITE => 56,
            Symbol::BLACK => 13,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))->label($end);

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function scholar_checkmate_labelled()
    {
        $board = (new ScholarCheckmate(new Board()))->play();

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $end = end($balance);

        $expected = [
            Symbol::WHITE => 12,
            Symbol::BLACK => 57,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))->label($end);

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function benko_gambit_labelled()
    {
        $board = (new BenkoGambit(new Board()))->play();

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $end = end($balance);

        $expected = [
            Symbol::WHITE => 127,
            Symbol::BLACK => 33,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))->label($end);

        $this->assertEquals($expected, $label);
    }

    /**
     * @test
     */
    public function closed_sicilian_labelled()
    {
        $board = (new ClosedSicilian(new Board()))->play();

        $balance = (new HeuristicPicture($board->getMovetext()))
            ->take()
            ->getBalance();

        $end = end($balance);

        $expected = [
            Symbol::WHITE => 6,
            Symbol::BLACK => 10,
        ];

        $label = (new LinearCombinationLabeller(self::$permutations))->label($end);

        $this->assertEquals($expected, $label);
    }
}
