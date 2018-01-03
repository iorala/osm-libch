<?php
// Script to fetch library data from https://overpass-turbo.osm.ch/

// prepare overpass query
// The query looks for all nodes, ways and rels in an area

$query = "[out:json];
  area[name='Chur']->.a;
  ( node(area.a)[amenity=library];
    way(area.a)[amenity=library];
    rel(area.a)[amenity=library];
  );
  (._;>;);
  out;";

function query($query) {
	$overpass = 'http://overpass.osm.ch/api/interpreter?'.preg_replace("/\s+/", "", $query);


	$html = file_get_contents($overpass);
	$result = json_decode($html, true); // "true" to get PHP array instead of an object

	return $result;
}

function query_local($query) {
	// Test function using using local file 

	$overpass = 'query_local.json';


	$html = file_get_contents($overpass);
	$result = json_decode($html, true); // "true" to get PHP array instead of an object

	return $result;
}

$result = query_local($query);

//var_dump($result['elements']);


foreach ($result['elements'] as $element => $content) {
	// var_dump($content);

	if ($content["type"] == "node" && !isset($content['tags'])) {
		$empty_nodes[$content['id']] = $content; 
	}
}

foreach ($result['elements'] as $element => $content) {
	// var_dump($content);

	if (!isset($content['tags'])) {
		$empty_nodes[$content['id']] = array (
		 "lat" => $content["lat"],
		 "lon" => $content["lat"]
		);
	}
}


var_dump($empty_nodes);

?>