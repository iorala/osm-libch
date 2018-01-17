<?php

// Query funtion using the swiss overpass API interpreter for OSM
// Returns an Array
function query($query) {
	$overpass = 'http://overpass.osm.ch/api/interpreter?'.preg_replace("/\s+/", "", $query); // replacing all the whitespaces from query
	// Check if connection works
	try {					
		@$html = file_get_contents($overpass);
		if ($html == FALSE) {
			throw new Exception("Failed to connect to Overpass API. Please check connection and retry\n");
		}

		$result = json_decode($html, true); // "true" to get PHP array instead of an object

	} catch (Exception $e) {
		fwrite(STDERR, $e->getMessage());
		exit(1); // exit code != 0
	}

	return $result;
}

// Query test function using local file instead of the overpass API
function query_test($query) {
	$overpass = 'examples/query_test_TI.json'; // Small file containing all libraries from the canton ticino using ways, rels and nodes.
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
	//var_dump($way_elements);
	$first_node = $way_elements[0];
	//var_dump($first_node);
	//echo "\n------\n";
	$return_value["lat"] = $empty_elements[$first_node]["lat"];
	$return_value["lon"] = $empty_elements[$first_node]["lon"];

	return $return_value;
}

// Function for resolving relations. It returns the coordinates of the first node of the first way of the relation.
function resolve_rel($rel_elements, $empty_elements) {
	//var_dump($rel_elements);
	$first_way = $rel_elements[0];
	//var_dump($first_way);
	//echo "\n------\n";
	//var_dump($empty_elements[$first_way["ref"]]["nodes"]);
	$return_value = resolve_way($empty_elements[$first_way["ref"]]["nodes"], $empty_elements);
	//echo "Relation: ";
	//var_dump($return_value); 
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
		        $output_tags["id"] = $content["id"];
		        $output_tags["type"] = $content["type"];
		        $output[] = $output_tags;
		        break;

		    case "way":
		    	$output_tags = select_tags($content["tags"],$tags);  
		    	$output_coordinates = resolve_way($content["nodes"],$empty_elements);  
		        $output_tags["lat"] = $output_coordinates["lat"]; 
		        $output_tags["lon"] = $output_coordinates["lon"]; 
		        $output_tags["id"] = $content["id"];
		        $output_tags["type"] = $content["type"];
		        $output[] = $output_tags;
		        break;

		    case "relation":
		    	$output_tags = select_tags($content["tags"],$tags);  
		    	$output_coordinates = resolve_rel($content["members"],$empty_elements);
		        $output_tags["lat"] = $output_coordinates["lat"]; 
		        $output_tags["lon"] = $output_coordinates["lon"];
		        $output_tags["id"] = $content["id"];
		        $output_tags["type"] = $content["type"];
		        $output[] = $output_tags; 
		        break;
			}
		}
	return $output;
}
?>