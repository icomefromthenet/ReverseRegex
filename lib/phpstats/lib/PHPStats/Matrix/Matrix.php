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
    
namespace PHPStats\Matrix;
    
    
use PHPStats\Exception as PHPStatsException,
    PHPStats\Exception as MatrixException;
    
/**
* Matrix class
* 
* Class representing a matrix and exposing useful instance and static function
* for manipulating matrices.
* 
* @author Michael Cordingley <Michael.Cordingley@gmail.com>
* @since 0.0.4
*/
class Matrix
{
    /**
      *  @var array the matrix info 
      */
    protected $matrix = array();


    /**
     * Constructor function
     * 
     * Creates a new matrix object.  As an example, when given a string like
     * '[1, 2, 3; 4, 5, 6; 7, 8, 9]', then it will construct a new matrix
     * with 1, 2, and 3 as the values of the first row and 7, 8, and 9
     * as the values of the last row.
     * Also accepts arrays, so array(array(1, 2, 3), array(4, 5, 6) array(7, 8, 9)
     * is equivalent to the above example.
     * 
     * @param string $ A string representing a matrix literal or an array to convert into a matrix
     * @return matrix A new matrix object.
     */
    public function __construct($literal)
    {
	$this->matrix  = array();
	
	if (is_string($literal) === true) {
	    $literal = substr($literal, strpos($literal, '[') + 1, strpos($literal, ']') - strpos($literal, '[') - 1);
	    $rowStrings = explode(';', $literal);
	    
	    $i = 0;
	    
	    foreach ($rowStrings as $rowString) {
		    $this->matrix[$i] = array();
		    $j = 0;
		    
		    foreach (explode(',', $rowString) as $element) {
			    $this->matrix[$i][$j] = (float)$element;
			    $j++;
		    }
		    
		    $i++;
	    }
	}
	else if (is_array($literal) === true) {
	    $columns = 0;
	    foreach ($literal as $row) {
		    $columns = max($columns, count($row));
	    }
    
	    $i = 0;
	    foreach ($literal as $row) {
		if(!is_array($row)) {
			throw new MatrixException('Non-array row definition in array-based matrix construction.');
		}
		
		$this->matrix[$i] = array();
		$j = 0;

		foreach ($row as $element) {
			$this->matrix[$i][$j] = $element;
			$j++;
		}

		for (; $j < $columns; $j++) { //Zero fill incomplete rows
			$this->matrix[$i][$j] = 0;
		}

		$i++;
	    }
	}
	else {
		throw new MatrixException('Invalid matrix constructor options.');
	}
    }

    
    /**
      *  Convert the matrix to string 
      */
    public function __toString()
    {
	return $this->literal();
    }
    
    //  ----------------------------------------------------------------------------
    
    /**
     * Get Colunns function
     * 
     * Returns the number of colunns in the matrix
     * 
     * @return int The number of colunns in the matrix
     */
    public function getColumns()
    {
	return count($this->matrix[0]);
    }

    /**
     * Get Element function
     * 
     * Returns the matrix element at the specified location.
     * 
     * @param int $row The row in the matrix to return
     * @param int $column The column in the matrix to return
     * @return float The matrix element
     */
    public function getElement($row, $column)
    {
	return $this->matrix[$row - 1][$column - 1];
    }
    
    /**
      *  Fetch a matrix row
      *
      *  @return array the matrix row
      *  @access public
      */
    public function getRow($row)
    {
	return $this->matrix[$row -1];
    }
    

    /**
     * Get Rows function
     * 
     * Returns the number of rows in the matrix
     * 
     * @return int The number of rows in the matrix
     */
    public function getRows()
    {
	return count($this->matrix);
    }
    
    
    /**
     * Set Element function
     * 
     * Sets the matrix element at the specified location.
     * 
     * @param int $row The row in the matrix to set
     * @param int $column The column in the matrix to set
     */
    public function setElement($row, $column, $value)
    {
	$this->matrix[$row - 1][$column - 1] = $value;
    }

    /**
      *  Removes rows in array
      *
      *  @param integer the row number 1 based
      *  @access public
      *  @return boolean
      */
    public function removeRow($row)
    {
	
	$removed = false;
	
	# range check on row
	if($row < 0) {
	    throw new PHPStatsException('Row must be above 0');
	}
	
	if($row > count($this->matrix)) {
	    throw new PHPStatsException('Row index is greater than current total');
	}
	
	
	foreach($this->matrix as $row => $column) {
	    
	    if($this->matrix[$row - 1] === $row) {
		unset($this->matrix[$row - 1]);
		break;
	    }
	}
	
	
	return $removed;
	
    }
    

    //  ----------------------------------------------------------------------------


    /**
     * Literal function
     * 
     * Prints a string representation of a matrix, suitable for storing
     * the value or debugging.
     * 
     * @return string A string representation of the matrix, same as the constructor accepts
     */
    public function literal()
    {
	$literal = "[";

	for ($i = 1; $i <= $this->getRows(); $i++) {
		for ($j = 1; $j <= $this->getColumns(); $j++) {
			$literal .= $this->getElement($i, $j).',';				
		}
		$literal = substr($literal, 0, strlen($literal) - 1).";";
	}
	$literal = substr($literal, 0, strlen($literal) - 1)."]";

	return $literal;
    }


    

    
}
/* End of File */