# Research Results
## GitHub
Why we decided in favour of GitHub?

GitHub is a tool which is often used in the area of programming. It has a built-in version control, has a document history, allows collaborative typing and is open to the public.

## Overpass Swiss API
We chose the swiss Overpass API (http://overpass-turbo.osm.ch/) because of the following reasons:

PHP offers no direct API like Phyton and Node.js, therefor we decided to pull requests via the Overpass API.
+ The usage of Overpass is recommended and it is said to be a simple instrument. Other interfaces are more complex.
+ We only need the data concerning swiss libraries. Consequently, we do not need the global interface.
+ The response time of the swiss interface is notable shorter.
+ In contrast to the global interface, the swiss interfaces shows no downtime.

A drawback is the relative complex query language.

## PHP
The implementation is realised in PHP. The drawback is that the current Server has no PHP-Environment.

## Coordinates
For the time being, we use the first coordinate of polygons to point out libraries on the map. For an allocation which is more accurate one could calculate the centre of a polygon.
