<?php
// Script to fetch library data from https://overpass-turbo.osm.ch/

// Declarations: 
// List of the cantons 
//$cantons = array("AG", "AI", "AR", "BE", "BL", "BS", "FR", "GE", "GL", "GR", "JU", "LU", "NE", "NW", "OW", "SG", "SH", "SO", "SZ", "TG", "TI", "UR", "VD", "VS", "ZG", "ZH" );
// When using query_test, only use TI
$cantons = array("TI");

// Array of the required output tags according to docs/DataRequirements
$tags = array("amenity","name","operator","addr:postcode","addr:country","addr:city","addr:street","addr:housenumber","contact:email","contact:phone","contact:website","ref:isil ","wikipedia ","wikidata ","website ","lat","lon");

// Import required functions 
require_once 'functions.lib.php';



// Main loop for getting the data from all cantons 
foreach ($cantons as $canton) {

	// reset variables

	unset($libraries);
	unset($empty_elements);
	unset($transformed_data);
	$empty_elements = array();

	// Prepare overpass query
	// The query looks for all nodes, ways and rels in an area
	// The line  "(._;>;)" adds all the empty nodes and ways required for the libraries that are ways and relations  

	$query = "[out:json];
	  area[ref='" . $canton . "']->.a;
	  ( node(area.a)[amenity=library];
	    way(area.a)[amenity=library];
	    rel(area.a)[amenity=library];
	  );
	  (._;>;);
	  out;";
	
	$result = query_test($query);

	// Separating libraries from empty ways and nodes (used for resolving ways and relations) 
	foreach ($result['elements'] as $element => $content) {
		if ($content["type"] == "node" && !isset($content['tags'])) {
			$empty_elements[$content['id']] = $content; 
		} elseif ($content["type"] == "way" && !isset($content['tags'])) {
			$empty_elements[$content['id']] = $content; 
		} else {
			$libraries[] = $content;
		}
	}

	// Transforming the array, 
	$transformed_data = transform_data($libraries, $tags, $empty_elements);

	// get the number of libraries per canton
	
    $library_count[$canton] = count($transformed_data);

	//Dumping the cantons data for test purposes 
	echo $canton . ": \n" ;
	var_dump($transformed_data);
	echo "\n------\n";

}

// Dumping the number of libraries per canton 
var_dump($library_count)

?>