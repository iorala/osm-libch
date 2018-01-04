<?php

//$cantons = array()

// Array of all required output tags according to docs/DataRequirements

$tags = array("amenity","name","operator","addr:postcode","addr:country","addr:city","addr:street","addr:housenumber","contact:email","contact:phone","contact:website","ref:isil ","wikipedia ","wikidata ","website ","lat","lon");

// Prepare overpass query
// The query looks for all nodes, ways and rels in an area

$query = "[out:json];
  area[ref='TI']->.a;
  ( node(area.a)[amenity=library];
    way(area.a)[amenity=library];
    rel(area.a)[amenity=library];
  );
  (._;>;);
  out;";

?>