<?php

// Query funtion using the swiss overpass API interpreter for OSM
// Returns an Array
function query($query) {
	$overpass = 'http://overpass.osm.ch/api/interpreter?'.preg_replace("/\s+/", "", $query); // replacing all the whitespaces from query
	$html = file_get_contents($overpass);
	$result = json_decode($html, true); // "true" to get PHP array instead of an object

	return $result;
}

// Query test function using local file instead of the overpass API
function query_test($query) {
	$overpass = 'examples/query_test_TI.json'; // Small file containing all libraries from the canton ticino using ways, rels and nodes.
	//$overpass = 'examples/query_test_CH.json'; // File containing all swiss libraries
	$html = file_get_contents($overpass);
	$result = json_decode($html, true); // "true" to get PHP array instead of an object

	return $result;
}

// Function for returning all required tags from the element according.
function select_tags($tag_element,$tags) {
	foreach($tag_element as $tag => $value) {
		if (in_array($tag, $tags)) {
			$tag_list[$tag] = $value;
		}	
	}
	return $tag_list;
}

// Function resolving way elements. It returns the coordinates of the first node
function resolve_way($way_elements, $empty_elements) {
	$first_node = $way_elements[0];
	$return_value["lat"] = $empty_elements[$first_node]["lat"];
	$return_value["lon"] = $empty_elements[$first_node]["lon"];

	return $return_value;
}

// Funtion for resolving relations. It returns the coordinates of the first node of the first way of the relation.
function resolve_relation($rel_elements, $empty_elements) {
	$first_way = $rel_elements[0];
	$return_value = $resolve_relation($rel_elements["nodes"], $empty_elements);

	return $return_value;
}

// Function for transforming the library data according to the requirements. 
function transform_data($libraries, $tags, $empty_elements) {
	foreach ($libraries as $element => $content) {

		switch ($content["type"]) {
		    case "node":
		    	$output_tags = select_tags($content["tags"],$tags);        
		        $output_tags["lat"] = $content["lat"]; 
		        $output_tags["lon"] = $content["lon"]; 
		        $output[] = $output_tags;
		        break;

		    case "way":
		    	$output_tags = select_tags($content["tags"],$tags);  
		    	$output_coordinates = resolve_way($content["nodes"],$empty_elements);  
		        $output_tags["lat"] = $output_coordinates["lat"]; 
		        $output_tags["lon"] = $output_coordinates["lon"]; 
		        $output[] = $output_tags;
		        break;

		    case "rel":
		    	$output_tags = select_tags($content["tags"],$tags);  
		    	$output_coordinates = resolve_relation($content["members"],$empty_elements);
		        $output_tags["lat"] = $output_coordinates["lat"]; 
		        $output_tags["lon"] = $output_coordinates["lon"]; 
		        break;
			}
		}
	return $output;
}
?>