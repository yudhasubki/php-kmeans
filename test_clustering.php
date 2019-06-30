<?php 

include('Services/Clustering.php');

use Services\KMeans\Clustering;

$centeroids = [
    [8,8,6,0,2],
    [6,6,3,1,1],
    [5,5,4,1,0],
];

$attributes = [
    [8,8,6,0,2],
    [6,6,3,1,1],
    [3,3,3,2,0],
    [5,5,4,1,0],
    [10,10,3,2,0],
];

$cluster = new Clustering($centeroids,$attributes,"C");
echo "Counting Object Distance into Cluster Centeroid";
echo "<br>================================<br>";
$distanceCenteroid = $cluster->countDistanceCenteroid();
echo "<br>";
echo "Determine Cluster each Record";
echo "<br>";
echo "======================= <br>";
$clusterGrouping   = $cluster->determineCluster($distanceCenteroid);
$joinAttributes    = $cluster->joinClusterAttributes($clusterGrouping);
$meanEachRow      = $cluster->createNewCenteroid();
echo "<br>";
echo "Create New Centeroid Table";
echo "<br>";
echo "======================= <br>";
$creatingNewCenteroid = $cluster->calculateMean($meanEachRow);
echo "<br>";
echo "Counting Back into Centeroid";
echo "<br>";
echo "======================= <br>";
$result = $cluster->countObjectCenteroid($creatingNewCenteroid);
echo "<br>";
echo "Result";
echo "<br>";
echo "======================= <br>";
$grouping = $cluster->determineCluster($result);
