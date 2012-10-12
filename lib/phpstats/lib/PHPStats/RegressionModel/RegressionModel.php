<?php
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
 
namespace PHPStats\RegressionModel;


use PHPStats\Exception as PHPStatsException,
    PHPStats\BasicStats;

/**
 * RegressionModel class
 * 
 * Parent class to the various regression models.  Provides functions that are
 * common across all models and enforces the regression model interface.
 */
abstract class RegressionModel
{
    
    protected $beta;
    protected $alpha;
    protected $r;

    /**
      * @var PHPStats\BasicStats  
      */
    protected $basic;
    
    public function __construct($datax, $datay, BasicStats $stats)
    {
	$this->basic = $stats;
    }
   
    /**
    * predict Function
    * 
    * Predicts a value of y given a value of x.
    * 
    * @param $x float The independent variable
    * @return $y float The predicted dependent variable
    */
    abstract public function predict($x);

    
    /**
      *  Getch the basic stats dependency
      *
      *  @access public
      *  @return BasicStats
      */
    public function getBasicStats()
    {
	return $this->basic;
    }
    
    
    /**
     * getAlpha Function
     * 
     * Returns the alpha term in the regression model's regression equation
     * 
     * @return float The alpha term
     */
    public function getAlpha()
    {
	return $this->alpha;
    }

    /**
     * getBeta Function
     * 
     * Returns the beta term in the regression model's regression equation
     * 
     * @return float The beta term
     */
    public function getBeta()
    {
	return $this->beta;
    }

    /**
     * getCorrelation Function
     * 
     * Returns the correlation coefficient between the x and y terms given
     * to the regression model.  This does account for any non-linear
     * relationships in the model, and is therefore superiod to simply
     * running a correlation($datax, $datay) on the two series.
     */
    public function getCorrelation()
    {
	return $this->r;
    }
}
/* End of File */