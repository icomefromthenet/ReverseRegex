<?php
namespace PHPStats\Tests\Base;

use PHPStats\PDistribution\Normal as Normal,
    PHPStats\PCalculator\Normal as NormalCalculator,
    PHPStats\StatisticalTests as StatisticalTests,
    PHPStats\Generator\SrandRandom,
    PHPStats\PCalculator\ChiSquare as ChiSquareCalculator,
    PHPStats\PCalculator\StudentsT as StudentsTCalculator,
    PHPStats\BasicStats;

    
class PDTest extends  \PHPUnit_Framework_TestCase
{
    /**
      *  @var  PHPStats\StatisticalTest
      */
    protected $statisticalTests;
    
    /**
      *  @var  PHPStats\Generator\SrandRandom
      */
    protected $randomGenerator;
    
    /**
      *  @var PHPStats\BasicStats 
      */
    protected $basicStats;
    
    /**
      * @var PHPStats\PCalculator\ChiSquare   
      */
    protected $chiSquare;
    
    /**
      *  @var  PHPStats\PCalculator\StudentsT
      */
    protected $studentsT;

    /**
      *  Class Constructor 
      */
    public function __construct()
    {
	$generator = new SrandRandom();
	$basic     = new BasicStats();
	$chiSquare = new ChiSquareCalculator($generator,$basic);
	$studentsT = new StudentsTCalculator($generator,$basic,$chiSquare);
    
	$this->statisticalTests = new StatisticalTests($basic,$chiSquare,$studentsT);
        $this->randomGenerator  = $generator;
        $this->basicStats       = $basic;
        $this->chiSquare        = $chiSquare;
        $this->studentsT        = $studentsT;
    }
    
}

/* End of File */