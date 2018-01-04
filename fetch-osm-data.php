<?php
// Script to fetch library data from https://overpass-turbo.osm.ch/

// prepare overpass query
// The query looks for all nodes, ways and rels in an area

require_once 'definitions.lib.php';
require_once 'functions.lib.php';


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

// Transforming the Array, 
$transformed_data = transform_data($libraries, $tags, $empty_elements);

var_dump($transformed_data);

?>