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

use PHPStats\BasicStats;

/**
  *  Math Class for matrix math
  *  
  *  @since 0.0.4
  *  @author Michael Cordingley <Michael.Cordingley@gmail.com>
  */
class MatrixMath
{
    /**
      *  @var MatrixBuilder the builder instance 
      */
    protected $builder;
    
    /**
      *  @var MatrixUtil 
      */
    protected $util;
    
    /**
      *  @var BasicStats 
      */
    protected $basic;
    
    /**
      *  @var Matrix 
      */
    protected $currentMatrix;
    
    /**
      *  Class used to compute matrix calculations 
      */
    public function __construct(MatrixBuilder $builder,MatrixUtil $util, BasicStats $stats)
    {
        $this->builder       = $builder;
        $this->util          = $util;
        $this->basic         = $stats;
	$this->currentMatrix = null;
    }
    
    
    
    //  ----------------------------------------------------------------------------
    
    
    /**
    * Adds two matrices together
    * 
    * @param matrix $matrixA the first matrix
    * @param matrix $matrixB The second matrix to add
    * @return matrix The summed matrix
    * @access public
    * 
    */
    public function add(Matrix $matrixA,Matrix $matrixB)
    {
	$this->util->sizeCheck($matrixA, $matrixB);

	$rows      = $matrixA->getRows();
	$columns   = $matrixA->getColumns();
	$newMatrix = $this->builder->zero($rows, $columns);

	for ($i = 1; $i <= $rows; $i++) {
		for ($j = 1; $j <= $columns; $j++) {
			$newMatrix->setElement($i, $j, $matrixA->getElement($i, $j) + $matrixB->getElement($i, $j));
		}
	}

	return $newMatrix;
    }
    
    /**
     * Subtract function
     * 
     * Subtracts a matrix from the current matrix
     *
     * @param matrix $matrixA The matrix to subtract
     * @param matrix $matrixB The matrix to subtract
     * @return matrix The subtracted matrix
     */
    public function subtract(Matrix $matrixA,Matrix $matrixB)
    {
            $this->util->sizeCheck($matrixA, $matrixB);

            $rows      = $matrixA->getRows();
            $columns   = $matrixA->getColumns();
            $newMatrix = $this->builder->zero($rows, $columns);

            for ($i = 1; $i <= $rows; $i++) {
                    for ($j = 1; $j <= $columns; $j++) {
                            $newMatrix->setElement($i, $j, $matrixA->getElement($i, $j) - $matrixB->getElement($i, $j));
                    }
            }

            return $newMatrix;
    }

   /**
    * Reduce function
    * 
    * Returns a matrix with the selected row and column removed, useful for
    * calculating determinants or other recursive operations on matrices.
    * 
    * @param Matrix the matrix to reduce
    * @param int $row Row to remove, null to remove no row
    * @param int $column Column to remove, null to remove no column
    * @return matrix A new, smaller matrix
    */
   public function reduce(Matrix $matrix, $row = null, $column = null)
   {
        $literal = "[";

        for ($i = 1; $i <= $matrix->getRows(); $i++) {
                
                if ($i == $row) {
                    continue;
                }
                
                for ($j = 1; $j <= $matrix->getColumns(); $j++) {
                        
                        if ($j == $column) {
                            continue;
                        }
                        
                        $literal .= $matrix->getElement($i, $j).',';				
                }
                
                $literal = substr($literal, 0, strlen($literal) - 1).";";
        }
        
        $literal = substr($literal, 0, strlen($literal) - 1)."]";

        return new Matrix($literal);
   }
    
    /**
    * Adjoint function
    * 
    * Computes the adjoint of the matrix
    * 
    * @return matrix The matrix's adjoint
    * @param matrix the matrix to calculate adjoint
    */
   public function adjoint(Matrix $matrix)
   {
        $this->util->checkSquare($matrix);

        $newMatrix  = $this->builder->zero($matrix->getRows(), $matrix->getColumns());
	$tmpMatrix = $this->builder->transpose($matrix); 
	
        for ($i = 1; $i <= $matrix->getRows(); $i++) {
                for ($j = 1; $j <= $matrix->getColumns(); $j++) {
                        $reduce = $this->reduce($tmpMatrix,$i, $j);
                        $newMatrix->setElement($j, $i, pow(-1, $i + $j) * $this->determinant($reduce)); //May as well do the transpose here
                }
        }

        return $newMatrix;
   }

   /**
    * Determinant function
    * 
    * Returns the determinant of the matrix
    *
    * @param Matrix $matrix
    * @return float The matrix's determinant
    */
   public function determinant(Matrix $matrix)
   {
        $this->util->checkSquare($matrix);

        if ($matrix->getRows() == 1) {
                return $matrix->getElement(1, 1);
        }

        $sum = 0;
        $i = 1; //Statically choose the first row for cofactor expansion
        for ($j = 1; $j <= $matrix->getColumns(); $j++) {
                $reduce = $this->reduce($matrix,$i, $j);
                $sum += pow(-1, $i + $j) * $matrix->getElement($i, $j) * $this->determinant($reduce);
        }
        return $sum;
   }

   /**
    * Dot Multiply function
    * 
    * Multiplies this matrix against a second matrix
    *
    * @param matrix $matrixA The first matrix in the multiplication
    * @param matrix $matrixB The second matrix in the multiplication
    * @return matrix The multiplied matrix
    */
   public function dotMultiply(Matrix $matrixA ,Matrix $matrixB)
   {
        $this->util->multiplyCheck($matrixA, $matrixB);

        $rows    = $matrixA->getRows();
        $columns = $matrixB->getColumns();

        $newMatrix = $this->builder->zero($rows, $columns);

        for ($i = 1; $i <= $rows; $i++) {
                
                for ($j = 1; $j <= $columns; $j++) {
                        
                        $row = $matrixA->getRow($i);

                        $column = array();
                        
                        for ($k = 1; $k <= $rows; $k++) {
                             
                             $column[] = $matrixB->getElement($k, $j);
                        }

                        $newMatrix->setElement($i, $j, $this->basic->sumXY($row, $column));
                }
        }

        return $newMatrix;
   }
   
   
   /**
    * Power function
    * 
    * Raises the matrix to the $power power.
    *
    * @param Matrix $matrix
    * @param float $power The power to which to raise the matrix.
    * @return matrix The matrix to the $power power
    */
   public function pow(Matrix $matrix, $power)
   {
        $this->util->checkSquare($matrix);

        $newMatrix = $this->builder->identity($matrix->getRows());

        for ($i = 0; $i < $power; $i++) {
             $newMatrix = $this->dotMultiply($newMatrix,$matrix);
        }

        return $newMatrix;
   }
   
   
   
    /**
     * Scalar Multiply function
     * 
     * Multiplies this matrix against a scalar value
     *
     * @param Matrix $matrix
     * @param float $scalar The scalar value
     * @return matrix The multiplied matrix
     */
    public function scalarMultiply(Matrix $matrix,$scalar)
    {
        $rows = $matrix->getRows();
        $columns = $matrix->getColumns();
        
        $newMatrix = $this->builder->zero($rows, $columns);

        for ($i = 1; $i <= $rows; $i++) {

                for ($j = 1; $j <= $columns; $j++) {
                        $newMatrix->setElement($i, $j, $matrix->getElement($i, $j) * $scalar);
                }
        }

        return $newMatrix;
    }
    
    
    /**
     * Inverse function
     * 
     * Returns the inverse of the matrix
     *
     * @param Matrix $matrix
     * @return matrix The matrix's inverse
     * @access public
     */
    public function inverse(Matrix $matrix)
    {
	$newmatrix =  $this->adjoint($matrix);
        $mutliply  = (1 / $this->determinant($newmatrix));

        return $this->scalarMultiply($newmatrix,$mutliply);
    }
    
    
    
    /**
     * Trace function
     * 
     * Returns the trace of a square matrix
     *
     * @param Matrix $matrix
     * @return float The matrix's trace
     */
    public function trace(Matrix $matrix)
    {
	$this->util->checkSquare($matrix);

	$trace = 0;
	for ($i = 1; $i <= $matrix->getRows(); $i++) {
		$trace += $matrix->getElement($i, $i);
	}
	return $trace;
    }

    
}
/* End of File */