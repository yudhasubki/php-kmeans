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
echo "Counting Object Distance into Cluster Centeroid \n";
echo "================================ \n";
$distanceCenteroid = $cluster->countDistanceCenteroid();
echo "\n";
echo "Determine Cluster each Record";
echo "\n";
echo "======================= \n";
$clusterGrouping   = $cluster->determineCluster($distanceCenteroid);
$joinAttributes    = $cluster->joinClusterAttributes($clusterGrouping);
$meanEachRow      = $cluster->createNewCenteroid();
echo "\n";
echo "Create New Centeroid Table";
echo "\n";
echo "======================= \n";
$creatingNewCenteroid = $cluster->calculateMean($meanEachRow);
echo "\n";
echo "Counting Back into Centeroid";
echo "\n";
echo "======================= \n";
$result = $cluster->countObjectCenteroid($creatingNewCenteroid);
echo "\n";
echo "Result";
echo "\n";
echo "======================= \n";
$grouping = $cluster->determineCluster($result);
