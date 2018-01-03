<?php
// Script to fetch library data from https://overpass-turbo.osm.ch/

// prepare overpass query
// The query looks for all nodes, ways and rels in an area

 
//$cantons = array()
$tags = array("amenity","name","operator","addr:postcode","addr:country","addr:city","addr:street","addr:housenumber","contact:email","contact:phone","contact:website","ref:isil ","wikipedia ","wikidata ","website ","lat","lon");


$query = "[out:json];
  area[ref='TI']->.a;
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
	// The testing file contains all libraries from the canton ticino. The libraries use ways, rels and nodes

	$overpass = 'query_local.json';

	$html = file_get_contents($overpass);
	$result = json_decode($html, true); // "true" to get PHP array instead of an object

	return $result;
}

function select_tags($tag_element,$tags) {
	foreach($tag_element as $tag => $value) {
		if (in_array($tag, $tags)) {
			$tag_list[$tag] = $value;
		}
		
	}
	return $tag_list;
}

function resolve_way($way_elements, $empty_elements) {

	$first_node = $way_elements[0];
	$return_value["lat"] = $empty_elements[$first_node]["lat"];
	$return_value["lon"] = $empty_elements[$first_node]["lon"];

	//$return_value["lat"] = $empty_nodes[$way_element["nodes"][0]]["lat"]; 

	return $return_value;

}

function resolve_relation($rel_elements, $empty_elements) {
	$first_way = $rel_elements[0];

	$return_value = $resolve_relation($rel_elements["nodes"], $empty_elements);

	return $return_value;
}

$result = query_local($query);


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


// Transforming the Array
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
	    	$output_coordinates = resolve_rel($content["members"],$empty_elements);
	        
	        $output_tags["lat"] = $output_coordinates["lat"]; 
	        $output_tags["lon"] = $output_coordinates["lon"]; 

	        break;
		}
	
	}


var_dump($output);

?>