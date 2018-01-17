# osm-libch

## Purpose 
osm-libch is a PHP script for fetching OSM Metadata of Swiss libraries. It was developed during a study project at the [HTW Chur](http://www.htwchur.ch) 
It queries the [Swiss Overpass API](http://overpass.osm.ch/) for all nodes, ways or relations that are tagged as library ("amenity":"library"). It does this separately for each canton. It then extracts the coordinates and selected tags and writes them in a flat JSON structure. The result is a collection of JSON files: One for each canton, as well as a compilation of all libraries. The number of libraries of each canton are also tracked. 

This data can then be used for display in a web page. 

Documentation can be found in the [docs directory](docs/)

## Requirements 

+ PHP environment PHP 5.4+
+ Network connection
+ Write access to the working directory

## Using the software 

To run the software type: `php osm-libch.ph`
This will create a directory called 'libs' containing the JSON files. Existing files are overwritten.
The software won't print any output unless an error occurs. Errors are printed to stderr before exiting with error code 1.

Alternatively the software can be installed on a web server and started from a browser. This will give the same output, but affects error handling. 

The software is preferably run as a CRON-Job. Please do not use it to fetch data JIT. The Swiss Overpass API is run by volunteers. 

For testing and debugging purposes the software can be run in Test-mode. See the switch 
[at the beginning of osm-libch.php](https://github.com/iorala/osm-libch/blob/e17fc7ba3dd5b6daacb1fc4f945f93069fd1cd33/osm-libch.php#L8)


## License 

osm-libch is distributed under the GPL 3.0 license.

All Openstreetmap data is made available under [ODbL](https://opendatacommons.org/licenses/odbl/).
