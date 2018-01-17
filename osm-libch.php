<?php
// Script to fetch library data from https://overpass-turbo.osm.ch/

// Variables: 

// For testing and debugging set to TRUE

$test = FALSE;

// List of the cantons 
$cantons = array("AG", "AI", "AR", "BE", "BL", "BS", "FR", "GE", "GL", "GR", "JU", "LU", "NE", "NW", "OW", "SG", "SH", "SO", "SZ", "TG", "TI", "UR", "VD", "VS", "ZG", "ZH" );

// Array of the required output tags according to docs/DataRequirements
$tags = array("amenity","name","operator","addr:postcode","addr:country","addr:city","addr:street","addr:housenumber","contact:email","contact:phone","contact:website","ref:isil","wikipedia ","wikidata ","website ","lat","lon");

// Import required functions 
require_once 'functions.lib.php';

// When testing only use data for TI
if ($test) {
	$cantons = array("TI");
}


// Create folder for library documents

if (!is_dir("libs")) {
	if (is_file("libs")) {
		fwrite(STDERR, "Can't create directory 'libs'. File 'libs' is in the way\n");
		exit(1); // exit code != 0
	} else {
		mkdir("libs");
	}
}

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
	
	// When testing or debugging use query_test() which relies on local data
	if ($test) {
		$result = query_test($query);
	} else {
		$result = query($query);
	}
	

	// Separating libraries from empty ways and nodes (used for resolving ways and relations) 

	foreach ($result['elements'] as $element => $content) {
		if ($content["type"] == "node" && !isset($content['tags']['amenity'])) { 
			$empty_elements[$content['id']] = $content; 
		} elseif ($content["type"] == "way" && !isset($content['tags']['amenity'])) {
			$empty_elements[$content['id']] = $content; 
		} else {
			$libraries[] = $content;
		}
	}

	// Transforming the array, 
	$transformed_data = transform_data($libraries, $tags, $empty_elements);

	// get the number of libraries per canton
	
    $library_count[$canton] = count($transformed_data);


	// Create HTML dumps for testing and debugging from a browser
    if ($test) {
    	foreach ($transformed_data as $element) {
			echo $canton . "<br />\n";
		foreach($element as $key => $value){
			echo $key." ".$value."<br />\n";
			}
		}
    }
	
	
	// Count the libraries
	$libcount = count($transformed_data);
	$total_count += $libcount;


	// Output, separated for each library
		$output = array_slice($transformed_data, 0, $libcount); //choses the correct array
		$arrlib = json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); 
		$file = "libs/libraries" . "_" . $canton . ".json";
		file_put_contents($file, $arrlib);
	}
	
// Overall output for libraries
$data_libs = json_encode($transformed_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

$file = 'libs/libraries.json';
file_put_contents($file, $data_libs);


// Dumping the number of libraries per canton in txt
$library_count["Total CH"] = $total_count;
$canton_count = json_encode($library_count, JSON_PRETTY_PRINT);

$file = 'libs/library_count.json';
file_put_contents($file, $canton_count);

?>
