<?php
namespace PHPStats\Tests\Clustering;

use PHPStats\Clustering\Kmeans as Kmeans,
    PHPStats\BasicStats,
    PHPStats\Matrix\Matrix as Matrix,
    PHPStats\Matrix\MatrixBuilder,
    PHPStats\Matrix\MatrixMath,
    PHPStats\Matrix\MatrixUtil,
    PHPStats\Generator\SrandRandom;
    

class KmeansTest extends \PHPUnit_Framework_TestCase
{
    private $kmeans;
    
    function __construct()
    {
	$observations = array();
	$builder      = new MatrixBuilder();
	$util         = new MatrixUtil();
	$basic        = new BasicStats();
	$generator    = new SrandRandom();
	
	$math         = new MatrixMath($builder,$util,$basic);
	
	for ($i = 0; $i < 200; $i++) { // First cluster, centered near (2.5, 2.5)
		//1 x 2 for 2D data, e.g. array(array(x, y))  One row, so the outer array has only one element.
		$observations[] = new Matrix(array(array(mt_rand(0, 5), mt_rand(0, 5))));
	}

	for ($i = 0; $i < 200; $i++) { // Second cluster, centered near (12.5, 12.5)
		$observations[] = new Matrix(array(array(mt_rand(0, 5) + 10, mt_rand(0, 5) + 10)));
	}

	$this->kmeans = new KMeans($observations, 2,$generator,$builder,$math); //Search for our two clusters
    }
    
    function testCalculations()
    {
	$centroids = $this->kmeans->getCentroids();

	$this->assertEquals(2, count($centroids)); //Number of centroids

	$observations = $this->kmeans->getObservations();
	
	//First 200 observations should be in the same cluster (specfic cluster index is random)
	$firstCluster = $observations[0]['centroid'];
	for ($i = 0; $i < 200; $i++) $this->assertEquals($firstCluster, $observations[$i]['centroid']);

	//Second 200 observations should be in the same cluster (specfic cluster index is random)
	$secondCluster = $observations[200]['centroid'];
	for ($i = 200; $i < 400; $i++) $this->assertEquals($secondCluster, $observations[$i]['centroid']);
    }
}
/* End of File */