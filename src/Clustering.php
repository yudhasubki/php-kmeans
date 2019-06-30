<?php 

namespace Services\KMeans;

class Clustering {

    private $centeroids;
    private $attributes;
    private $prefix;
    private $clusterNumber;

    /**
     * @param array  | Centeroids is total of your table centeroid
     * @param array  | attributes 
     * @param string | Tag prefix ex = Cluster,C or etc
     * @param int    | Cluster Number is total cluster do you want *note depends on your centeroid
     */
    public function __construct($centeroids = array(), $attributes = array(),$prefix){
        $this->centeroids    = $centeroids;
        $this->attributes    = $attributes;
        $this->prefix        = $prefix;
        $this->clusterNumber = count($centeroids);
    }
    
    /**
     * Counting Object into Cluster Centeroid
     * @param array
     * @param array
     * @return array
     */
    public function countDistanceCenteroid() : array {
        try{
            $results = [];
            for($cluster = 0;$cluster < count($this->attributes); $cluster++){
                $grouping = [];
                for($centeroid = 0;$centeroid < count($this->centeroids); $centeroid++){
                    $result = [];
                    for($attribute = 0;$attribute < count($this->attributes[$cluster]); $attribute++){
                        $distanceCenteroid = abs($this->centeroids[$centeroid][$attribute] - $this->attributes[$cluster][$attribute]);
                        $eachDistance = $distanceCenteroid * $distanceCenteroid;
                        array_push($result,$eachDistance);
                    }
                    $clusterDistance = array_sum($result);
                    $squareDistance = sqrt($clusterDistance);
                    array_push($grouping,$squareDistance);
                }
                array_push($results,$grouping);   
            }
    
            // Use this if want show results of cluster and record
            for($cluster = 0;$cluster < count($results); $cluster++){
                for($record = 0;$record < count($results[$cluster]); $record++){
                    echo Sprintf('Cluster %s Record %s : %s', $record+1, $cluster+1, $results[$cluster][$record])."\n";
                }
            }

            if(is_array($results)){
                return $results;
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }

        return [];
    }

    /**
     * Determine where record must positioned
     * @param array
     * @return array
     */
    public function determineCluster($clusterGroupings = array()) : array {
        $resultsOfDetermine = [];
        $positions = [];
        if(is_array($clusterGroupings)){
            for($grouping = 0; $grouping < count($clusterGroupings); $grouping++){
                $result = [];
                $temp = 0;

                for($determine = 0; $determine < count($clusterGroupings[$grouping]);$determine++){
                    $clusterPosition = array();
                    $clusterMinimum = min($clusterGroupings[$grouping]);

                    if($clusterGroupings[$grouping][$determine] == $clusterMinimum) {
                        $whichCluster = $determine;
                        $clusterPosition["value"] = $clusterMinimum;

                        for($number = 0; $number < $this->clusterNumber; $number ++){
                            if($whichCluster == $number)
                                $clusterPosition['position'] = Sprintf("%s%s",$this->prefix,$whichCluster+1);
                        }
                        array_push($resultsOfDetermine,$clusterPosition);
                    }
                }
            }
	    }

        for($position = 0; $position < count($resultsOfDetermine); $position++){
            echo Sprintf("Position %s with value %s", $resultsOfDetermine[$position]['position'], $resultsOfDetermine[$position]["value"])."\n";
        }
        
        if(is_array($resultsOfDetermine)){
            return $resultsOfDetermine;
        }

        return [];
    }

    /**
     * Join where is cluster positioned 
     * ex : C1,C2, or C3
     * @param array
     * @return void
     */
    public function joinClusterAttributes($clusterDetermine = array()) : void 
    {
        try{
            for($attribute = 0; $attribute < count($clusterDetermine); $attribute++){
                $this->attributes[$attribute]['position'] = $clusterDetermine[$attribute]['position'];
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    /**
     * Creating new centeroid table
     * @return array
     */
    public function createNewCenteroid() : array{
        try{
            $groupingName = [];
            $centeroids = [];
            
            for($name = 0; $name < $this->clusterNumber; $name++){
                array_push($groupingName, Sprintf("%s%s", $this->prefix,$name+1));
            }
            
            for($group = 0; $group < count($groupingName); $group++){
                $groupingClustered = [];
                for($attribute = 0; $attribute < count($this->attributes); $attribute++){
                    if($groupingName[$group] == $this->attributes[$attribute]['position']){
                        array_push($groupingClustered,$this->attributes[$attribute]);    
                    }
                }
                array_push($centeroids,$groupingClustered);
            }

            $resultEachRow = [];
            for($centeroid = 0; $centeroid < count($centeroids); $centeroid++){
                $centeroidGrouping = [];
                 for($mean = 0; $mean < count($centeroids[$centeroid]); $mean++){
                    $grouping = [];
                    for($calculate = 0; $calculate < count($centeroids[$centeroid][$mean]) - 1; $calculate++){
                        $sumOfCluster = count($centeroids[$centeroid]);
                        $resultMean   = $centeroids[$centeroid][$mean][$calculate] / $sumOfCluster;
                        array_push($grouping,$resultMean);
                    }
                    array_push($centeroidGrouping,$grouping);
                }
                array_push($resultEachRow,$centeroidGrouping);
            }

            return $resultEachRow;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    /**
     * Calculate Mean
     * @param array
     * @return array
     */
    public function calculateMean($resultMeanEach = array()) : array {
        try{
            $resultCalculate = [];
            for($result = 0;$result < count($resultMeanEach); $result++){
                for($meanGroup = 0; $meanGroup < count($resultMeanEach[$result]); $meanGroup++){
                    $meanSum = [];
                    for($mean = 0; $mean < count($resultMeanEach[$result][$meanGroup]); $mean++){
                        $total = 0;
                        for($row = 0; $row < count($resultMeanEach[$result]); $row++){
                            $total += $resultMeanEach[$result][$row][$mean];
                        }
                        array_push($meanSum,$total);
                    }
                    array_push($resultCalculate,$meanSum);
                }
            }
            $removeDuplicate = array_map("unserialize", array_unique(array_map("serialize", $resultCalculate)));

            $orderingMean = [];
            
            foreach($removeDuplicate as $removeKey => $removeValue){
                $mean = [];
                foreach($removeValue as $key => $value ){
                    array_push($mean,$value);
                }
                array_push($orderingMean,$mean);
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }finally{
            foreach($removeDuplicate as $removeKey => $removeValue){
                foreach($removeValue as $key => $value ){
                    echo $value.' | ';
                }
                echo "\n";
            }
        }

        if(is_array($orderingMean)){
            return $orderingMean;
        }
        
        return [];
    }

    /**
     * Counting Back Object into Centeroid
     * @param array
     * @return array
     */
    public function countObjectCenteroid($centeroids = array()) : array {
        try{
            $results = [];
            for($cluster = 0;$cluster < count($this->attributes); $cluster++){
                $grouping = [];
                for($centeroid = 0;$centeroid < count($centeroids); $centeroid++){
                    $result = [];
                    for($attribute = 0;$attribute < count($this->attributes[$cluster]) - 1; $attribute++){
                        $distanceCenteroid = abs($centeroids[$centeroid][$attribute] - $this->attributes[$cluster][$attribute]);
                        $eachDistance = $distanceCenteroid * $distanceCenteroid;
                        array_push($result,$eachDistance);
                    }
                    $clusterDistance = array_sum($result);
                    $squareDistance = sqrt($clusterDistance);
                    array_push($grouping,$squareDistance);
                }
                array_push($results,$grouping);   
            }
    
            // Use this if want show results of cluster and record
            for($cluster = 0;$cluster < count($results); $cluster++){
                for($record = 0;$record < count($results[$cluster]); $record++){
                    echo Sprintf('Cluster %s Record %s : %s', $record+1, $cluster+1, $results[$cluster][$record])."\n";
                }
            }

            if(is_array($results)){
                return $results;
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }

        return [];
    }
}
