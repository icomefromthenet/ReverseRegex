<?php
namespace PHPStats\PCalculator;

/**
 * PHP Statistics Library
 *
 * Copyright (C) 2011-2012 Michael Cordingley<Michael.Cordingley@gmail.com>
 * 
 * This library is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Library General Public License as published
 * by the Free Software Foundation; either version 3 of the License, or 
 * (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Library General Public
 * License for more details.
 * 
 * You should have received a copy of the GNU Library General Public License
 * along with this library; if not, write to the Free Software Foundation, 
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 * 
 * LGPL Version 3
 *
 * @package PHPStats
 */

use PHPStats\Exception as PHPStatsException,
    PHPStats\BasicStats,
    PHPStats\Generator\GeneratorInterface;	

/**
 * Calculator class
 * 
 * Parent class to all calculator.
 *
 * @author Michael Cordingley<Michael.Cordingley@gmail.com>
 * @since 0.0.4
 */
abstract class BasicCalculator
{
    
    /**
      *  @var PHPStats\BasicStats 
      */
    protected $basic;
    
    
    /**
      *  @var PHPStats\Generator\GeneratorInterface 
      */
    protected $generator;

    
    /**
      *  Class Constructor
      *
      *  @param GeneratorInterface $gen
      *  @param BasicStats $stats
      */    
    public function __construct(GeneratorInterface $gen, BasicStats $stats)
    {
	$this->basic     = $stats;
	$this->generator = $gen;
    }
    
    
    /**
      *  Fetch the random generator
      *
      *  @access public
      *  @return GeneratorInterface
      */
    public function getGenerator()
    {
	return $this->generator;
    }
    
    /**
      *  Set the random generator
      *
      *  @access public
      *  @param GeneratorInterface $generator
      */
    public function setGenerator(GeneratorInterface $generator)
    {
	$this->generator = $generator;	
    }
	
	
    /**
      *  Sets the BasicStats
      *
      *  @access public
      *  @param BasicStats $stats
      */	
    public function setBasicStats(BasicStats $stats)
    {
	$this->basic = $stats;
    }
    
    /**
      *  Gets the BasicStats
      *
      *  @return BasicStats
      *  @access public
      */
    public function getBasicStats()
    {
	return $this->basic;
    }
	

    //  ----------------------------------------------------------------------------
    # Utility Methods
		
    /**
      *   Fetch a random float value
      *
      *   @access protected
      *   @return float the random value between 0 and 1
      */
    protected function randFloat()
    {
	return  $this->generator->generate(0,$this->generator->max()) / $this->generator->max(); //A number between 0 and 1.
    }

    /**
      *  A Bernoulli Trial
      *
      *  @access protected
      *  @return integer 1 | 0
      */
    protected function bernoulliTrial($p = 0.5)
    {
	$standardVariate =$this->randFloat();
	return ($standardVariate <= $p) ? 1 : 0;
    }
    
}
/* End of File */