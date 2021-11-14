<?php

declare(strict_types=1);

namespace ReverseRegex\Test;

use PHPUnit\Framework\TestCase;
use Pimple\Pimple;
use ReverseRegex\PimpleBootstrap;

abstract class Basic extends TestCase
{
    public function createApplication()
    {
        $boot = new PimpleBootstrap();
        $pimple = $boot->boot(new Pimple());

        return $pimple;
    }
}
