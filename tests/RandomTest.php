<?php

declare(strict_types=1);

namespace ReverseRegex\Test;

use PHPUnit\Framework\TestCase;
use ReverseRegex\Random\MersenneRandom;
use ReverseRegex\Random\SimpleRandom;
use ReverseRegex\Random\SrandRandom;

class RandomTest extends TestCase
{
    public function testSimpleRandomIsDeterministicForAGivenSeed(): void
    {
        $generator = new SimpleRandom(5);

        $this->assertSame(750, $generator->generate(0, 1_000));
        $this->assertSame(657, $generator->generate(0, 1_000));
    }

    public function testSimpleRandomSameSeedSameSequence(): void
    {
        $a = new SimpleRandom(1_234);
        $b = new SimpleRandom(1_234);

        for ($i = 0; $i < 20; ++$i) {
            $this->assertSame($a->generate(0, 50), $b->generate(0, 50));
        }
    }

    public function testSimpleRandomStaysInsideTheRequestedRange(): void
    {
        $generator = new SimpleRandom(7);

        for ($i = 0; $i < 100; ++$i) {
            $value = $generator->generate(3, 9);
            $this->assertGreaterThanOrEqual(3, $value);
            $this->assertLessThanOrEqual(9, $value);
        }
    }

    public function testSimpleRandomCapsHugeMaximums(): void
    {
        $generator = new SimpleRandom(11);

        for ($i = 0; $i < 20; ++$i) {
            $this->assertLessThanOrEqual(2_796_203, $generator->generate(0, \PHP_INT_MAX));
            $this->assertLessThanOrEqual(2_796_203, $generator->generate(0));
        }
    }

    public function testSimpleRandomSeedIsNormalised(): void
    {
        $generator = new SimpleRandom(5);

        $this->assertSame(1, $generator->seed());
        $this->assertSame(6, $generator->seed(5));
        $this->assertSame(6, $generator->seed(-5));
    }

    public function testSimpleRandomWorksWithoutExplicitSeed(): void
    {
        $generator = new SimpleRandom();

        $value = $generator->generate(0, 10);

        $this->assertGreaterThanOrEqual(0, $value);
        $this->assertLessThanOrEqual(10, $value);
    }

    public function testSimpleRandomMinAndMax(): void
    {
        $generator = new SimpleRandom(1);

        $this->assertSame(2_147_483_647, $generator->max());
        $this->assertSame(0, $generator->min());
        $this->assertSame(10, $generator->max(10));
        $this->assertSame(10, $generator->max());
        $this->assertSame(2, $generator->min(2));
        $this->assertSame(2, $generator->min());
    }

    public function testSrandRandomIsReproducibleAfterReseeding(): void
    {
        $generator = new SrandRandom(42);
        $first = [$generator->generate(0, 1_000), $generator->generate(0, 1_000)];

        $generator->seed(42);
        $second = [$generator->generate(0, 1_000), $generator->generate(0, 1_000)];

        $this->assertSame($first, $second);
    }

    public function testSrandRandomSeedIsFluent(): void
    {
        $generator = new SrandRandom();

        $this->assertSame($generator, $generator->seed(1));
    }

    public function testSrandRandomUsesConfiguredMaximum(): void
    {
        $generator = new SrandRandom(3);

        $this->assertSame(mt_getrandmax(), $generator->max());
        $this->assertSame(0, $generator->min());
        $this->assertSame(5, $generator->max(5));
        $this->assertSame(5, $generator->max());

        for ($i = 0; $i < 50; ++$i) {
            $value = $generator->generate(0);
            $this->assertGreaterThanOrEqual(0, $value);
            $this->assertLessThanOrEqual(5, $value);
        }
    }

    public function testSrandRandomMin(): void
    {
        $generator = new SrandRandom(3);

        $this->assertSame(4, $generator->min(4));
        $generator->max(8);
        $this->assertSame(4, $generator->min());
        $this->assertSame(8, $generator->max());

        for ($i = 0; $i < 50; ++$i) {
            $value = $generator->generate(null);
            $this->assertGreaterThanOrEqual(4, $value);
            $this->assertLessThanOrEqual(8, $value);
        }
    }

    public function testMersenneRandomSameSeedSameSequence(): void
    {
        $a = new MersenneRandom(99);
        $sequenceA = [];
        for ($i = 0; $i < 10; ++$i) {
            $sequenceA[] = $a->generate(0, 1_000);
        }

        $b = new MersenneRandom(99);
        $sequenceB = [];
        for ($i = 0; $i < 10; ++$i) {
            $sequenceB[] = $b->generate(0, 1_000);
        }

        $this->assertSame($sequenceA, $sequenceB);
    }

    public function testMersenneRandomMatchesReferenceImplementation(): void
    {
        // First output of MT19937 for the reference seed 5489.
        $generator = new MersenneRandom(5_489);

        $this->assertSame(3_499_211_612, $generator->generate(0, 4_294_967_295));
    }

    public function testMersenneRandomStaysInsideTheRequestedRange(): void
    {
        $generator = new MersenneRandom(7);

        for ($i = 0; $i < 100; ++$i) {
            $value = $generator->generate(5, 10);
            $this->assertGreaterThanOrEqual(5, $value);
            $this->assertLessThanOrEqual(10, $value);
        }
    }

    public function testMersenneRandomWorksWithRandomSeed(): void
    {
        $generator = new MersenneRandom();

        $value = $generator->generate(0, 10);

        $this->assertGreaterThanOrEqual(0, $value);
        $this->assertLessThanOrEqual(10, $value);
    }

    public function testMersenneRandomMinAndMax(): void
    {
        $generator = new MersenneRandom(1);

        $this->assertSame(2_147_483_647, $generator->max());
        $this->assertSame(0, $generator->min());
        $this->assertSame(10, $generator->max(10));
        $this->assertSame(10, $generator->max());
        $this->assertSame(2, $generator->min(2));
        $this->assertSame(2, $generator->min());
    }
}
