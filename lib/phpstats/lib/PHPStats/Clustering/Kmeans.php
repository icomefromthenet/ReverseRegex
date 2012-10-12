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

namespace PHPStats\Clustering;


use PHPStats\Exception as PHPStatsException,
    PHPStats\Matrix\Matrix,
    PHPStats\Matrix\MatrixBuilder,
    PHPStats\Matrix\MatrixMath,
    PHPStats\Matrix\MatrixUtil,
    PHPStats\Generator\GeneratorInterface;


/**
* kmeans class
* 
* Performs a k-means clustering on a set of data.
* 
* For more information, see: http://en.wikipedia.org/wiki/K-means_clustering
*/
class Kmeans
{
    protected $centroids = array();
    protected $observations = array();

    /**
      *  @var  PHPStats\Generator\GeneratorInterface
      */
    protected $generator;
    
    /**
      *  @var  PHPStats\Matrix\MatrixBuilder
      */
    protected $mbuilder;
    
    /**
      *  @var  PHPStats\Matrix\MatrixMath
      */
    protected $mmath;
    
    /**
     * Constructor function
     * 
     * @param array $observations An array of 1 x n matrices, where n is how many dimensions the data has
     * @param int $k The number of clusters to use
     */
    public function __construct(array $observations, $k,GeneratorInterface $gen, MatrixBuilder $mBuilder,MatrixMath $mMath)
    {
	
	$this->generator = $gen;
	$this->mbuilder  = $mBuilder;
	$this->mmath     = $mMath;
	
	for ($i = 0; $i < $k; $i++) {
		$this->centroids[] = $observations[0]; //Fill centroids with vectors.  The specific values will be overwritten before use.
	}

	foreach ($observations as $observation) {
		$obs_array = array();
		$obs_array['coordinates'] = $observation;
		$obs_array['centroid'] = $this->generator->generate(0, $k - 1); //Randomly assign observations to centroids, according to the Random Partition method.
		
		$this->observations[] = $obs_array;
	}
	
	//Iterate until convergence is reached. i.e. when cluster assignments no longer change
	$change = true;
	while ($change) {
		$this->update();
		$change = $this->assign();
	}
    }
    
    /**
     * Get Observations function
     * 
     * Returns an array of arrays.  Each inner array is an associative array
     * of the 1 x n observation matrix and an integer representing the cluster
     * to which that observation has been assigned.
     * 
     * $inner_array['coordinates'] The observation matrix
     * $inner_array['centroid'] The assigned cluster
     * 
     * @return array An array of arrays that holds all observations.
     */
    public function getObservations()
    {
	return $this->observations;
    }
    
    /**
     * Get Centroids function
     * 
     * Returns an array of 1 x n matrices representing the centroids of the
     * clustering.  The array index is the cluster number.
     * 
     * @return array The array of 1 x n matrices
     */
    public function getCentroids()
    {
	return $this->centroids;
    }
    
    /**
      *  Assigns observations to the nearest centroid
      *
      *  @access private
      */
    protected function assign()
    {
	$change = false;
	$distance = array();
	
	foreach ($this->observations as &$observation) {
		$old_centroid = $observation['centroid'];
		
		foreach ($this->centroids as $index => $centroid) {
			$sumSquaredDistance = 0;
			$distanceVector = $this->mmath->subtract($observation['coordinates'],$centroid);

			for ($j = 1; $j <= $distanceVector->getColumns(); $j++) {
			    $sumSquaredDistance += pow($distanceVector->getElement(1, $j), 2);
			}

			$distance[$index] = sqrt($sumSquaredDistance); //Magnitude of the difference of the vectors
		}
		
		//Find the centroid with the least distance
		$observation['centroid'] = array_search(min($distance), $distance); //Should only be one, unless there's a tie, then just grab the first match.
		
		if ($observation['centroid'] != $old_centroid) {
		    $change = true;
		}
	}
	
	return $change; //If anything has changed during this pass
    }
    
    
    /**
      *  Centers centroids on current members
      *
      *  @access private
      */
    protected function update()
    {
	foreach ($this->centroids as $index => &$centroid) {
		$new_coordinates = $this->mbuilder->zero(1, $centroid->getColumns());
		$members = 0;
		
		foreach ($this->observations as $observation) {
			if ($observation['centroid'] == $index) {
				$new_coordinates = $this->mmath->add($new_coordinates,$observation['coordinates']);
				$members++;
			}
		}
		
		//Assign new value
		$centroid = $this->mmath->scalarMultiply($new_coordinates,(1/$members));
	}
    }
}
/* End of File */
