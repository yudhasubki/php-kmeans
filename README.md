# PHP KMeans Algorithm Library

K-means clustering is a method of vector quantization, originally from signal processing, that is popular for cluster analysis in data mining.

## How to use

Use the class in folder service then make an instance, example:

```bash
<?php

include('Services/Clustering.php');

use Services\KMeans\Clustering;

// your data centeroid
$centeroids = [
    [8,8,6,0,2],
    [6,6,3,1,1],
    [5,5,4,1,0],
];

// your data attributes
$attributes = [
    [8,8,6,0,2],
    [6,6,3,1,1],
    [3,3,3,2,0],
    [5,5,4,1,0],
    [10,10,3,2,0],
];

// make an instace to used a class kmeans
// centeroids data, data attributes, prefix show kmeans
$cluster = new Clustering($centeroids,$attributes,"C");

$clusterGrouping   = $cluster->determineCluster($distanceCenteroid);
$joinAttributes    = $cluster->joinClusterAttributes($clusterGrouping);
$meanEachRow       = $cluster->createNewCenteroid();

$creatingNewCenteroid = $cluster->calculateMean($meanEachRow);
$result = $cluster->countObjectCenteroid($creatingNewCenteroid);
$cluster->determineCluster($result);
```

## Usage

open terminal :

```
php /your/path/directory/your_file_name.php
```

or running on your browser

```
http://127.0.0.1:8001/your_file_name.php
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](https://choosealicense.com/licenses/mit/)
