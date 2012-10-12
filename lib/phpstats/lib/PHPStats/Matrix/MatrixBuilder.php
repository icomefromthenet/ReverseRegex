<?php
namespace PHPStats\Matrix;

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

use PHPStats\Exception as PHPStatsException;

/**
*  Builds a Matrix
*
*  @since 0.04
*  @author Michael Cordingley <Michael.Cordingley@gmail.com>
*/
class MatrixBuilder
{
  
  /**
   * Identity function
   * 
   * Returns an identity matrix of the specified size
   * 
   * @param int $rows The number of rows in the matrix
   * @param int $columns The number of columns in the matrix
   * @return matrix An identity matrix of the specified size
   */
  public function identity($size)
  {
    $literal = "[";
    
    for ($i = 0; $i < $size; $i++) {
            
            for ($j = 0; $j < $size; $j++) {
                    
                    if ($i == $j) {
                        $literal .= "1,";
                    }
                    else {
                        $literal .= "0,";
                    }
            }
            
            $literal = substr($literal, 0, strlen($literal) - 1).";";
    }
    
    $literal = substr($literal, 0, strlen($literal) - 1)."]";
    
    return new Matrix($literal);
  }

  /**
   * Uniform function
   * 
   * Returns a matrix consisting entirely of the supplied value
   * 
   * @param int $rows The number of rows in the matrix
   * @param int $columns The number of columns in the matrix
   * @param float $value The value with which to fill the matrix
   * @return matrix A matrix filled with elements of value $value
   */
  public function uniform($rows, $columns, $value = 0)
  {
    $literal = "[";

    for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $columns; $j++) {
                    $literal .= "0,";
            }
            $literal = substr($literal, 0, strlen($literal) - 1).";";
    }

    $literal = substr($literal, 0, strlen($literal) - 1)."]";
    
    return new Matrix($literal);
  }
  
  
  /**
  * Transpose function
  * 
  * Returns the transpose of the matrix
  *
  * @param Matrix $matrix
  * @return matrix The matrix's transpose
  */
 public function transpose(Matrix $matrix)
 {
    $rows      = $matrix->getRows();
    $columns   = $matrix->getColumns();
    $newMatrix = $this->zero($columns, $rows);

    for ($i = 1; $i <= $rows; $i++) {
            for ($j = 1; $j <= $columns; $j++) {
                    $newMatrix->setElement($j, $i, $matrix->getElement($i, $j));
            }
    }

    return $newMatrix;
 }
 

  /**
   * Zero function
   * 
   * Returns a matrix consisting entirely of zeroes
   * 
   * @param int $rows The number of rows in the matrix
   * @param int $columns The number of columns in the matrix
   * @return matrix A matrix filled with zero-valued elements
   */
  public function zero($rows, $columns)
  { 
    return $this->uniform($rows, $columns, 0);
  }

  /**
   * One function
   * 
   * Returns a matrix consisting entirely of ones
   * 
   * @param int $rows The number of rows in the matrix
   * @param int $columns The number of columns in the matrix
   * @return matrix A matrix filled with elements of value one
   */
  public function one($rows, $columns)
  {
        return $this->uniform($rows, $columns, 1);
  }

}
/* End of File */