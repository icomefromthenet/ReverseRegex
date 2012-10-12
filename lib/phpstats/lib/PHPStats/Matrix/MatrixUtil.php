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

use PHPStats\Exception as PHPStatsException,
    PHPStats\Exception as MatrixException;
    

/**
  *  Generic Utility Methods for matrixs
  *
  *  @since 0.0.4
  *  @author Michael Cordingley <Michael.Cordingley@gmail.com>
  */
class MatrixUtil
{
    
    
    /**
      *   Check to see if matrices are same size: m by n and m by n
      *   @access protected
      *   @param Matrix $matrixA
      *   @param Matrix $matrixB
      */
    public function sizeCheck(Matrix $matrixA, Matrix $matrixB)
    {
        if ($matrixA->getRows() != $matrixB->getRows() || $matrixA->getColumns() != $matrixB->getColumns()) {
            throw new MatrixException('Matrices are wrong size: '.$matrixA->getRows().' by '.$matrixA->getColumns().' and '.$matrixB->getRows().' by '.$matrixB->getColumns());
        }
            
        return true;
    }

    
    /**
      *  Check to see if matrices can be multiplied: m by n and n by p
      *
      *  @access protected
      *  @param Matrix $matrixA
      *  @param Matrix $matrixB
      */
    public function multiplyCheck(Matrix $matrixA, Matrix $matrixB)
    {
        if ($matrixA->getColumns() != $matrixB->getRows()) {
            throw new MatrixException('Matrices are wrong size: '.$matrixA->getRows().' by '.$matrixA->getColumns().' and '.$matrixB->getRows().' by '.$matrixB->getColumns());
        }
        
        return true;
    }

    
    /**
      *   Check to see if matrix is square: m by m
      *
      *   @access protected
      *   @param Matrix $matrix
      */
    public function checkSquare(Matrix $matrix)
    {
        if ($matrix->getColumns() != $matrix->getRows()) {
            throw new MatrixException('Matrices is not square: '.$matrix->getRows().' by '.$matrix->getColumns());
        }
        
        return true;
    }
    
}
/* End of File */