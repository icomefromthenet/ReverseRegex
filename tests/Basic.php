<?php

declare(strict_types=1);

namespace ReverseRegex\Tests;

use PHPUnit\Framework\TestCase;
use ReverseRegex\PimpleBootstrap;
use Pimple\Pimple;

abstract class Basic extends TestCase
{
    public function createApplication()
    {
        $boot = new PimpleBootstrap();
        $pimple = $boot->boot(new Pimple());
        return $pimple;
    }
}
