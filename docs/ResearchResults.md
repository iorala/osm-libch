# Research Results
## GitHub
Why we decided in favor of GitHub?

GitHub is a tool which is often used in the area of programming. It has a built-in version control, has a document history, allows collaborative typing and is open to the public.

## Overpass Swiss API
We chose the swiss Overpass API (http://overpass-turbo.osm.ch/) because of the following reasons:

PHP offers no direct API like Python and Node.js, therefor we decided to pull requests via the Overpass API.
+ The usage of Overpass is recommended and it is said to be a simple instrument. Other interfaces are more complex.
+ We only need the data concerning swiss libraries. Consequently, we do not need the global interface.
+ The response time of the swiss interface is notable shorter.
+ In contrast to the global interface, the swiss interfaces shows no downtime.

A drawback is the relative complex query language.

## PHP
PHP is used as language for the project. Unlike Python ore Node.js it can be deployed using most hosting providers. Furthermore we're both familiar with it. 


## Input datatypes 

Openstreetmap supports 3 datatypes: nodes, ways and relations. 

### Nodes 

Nodes are points on a map that have an id and coordinates. They can have other metadata in form of tags.  The majority of swiss libraries are of the type node. The coordinates can just be extracted from the object.

E.g. 
```json
{
  "type": "node",
  "id": 56698073,
  "lat": 47.2415699,
  "lon": 8.7313425,
  "tags": {
    "addr:city": "Stäfa",
    "addr:country": "CH",
    "addr:housenumber": "35",
    "addr:postcode": "8712",
    "addr:street": "Tränkebachstrasse",
    "amenity": "library",
    "contact:email": "bibliothek@lesegesellschaft.ch",
    "contact:phone": "+41 44 927 21 80",
    "contact:website": "http://www.lesegesellschaft.ch/bibliothek/",
    "name": "Gemeindebibliothek Stäfa",
    "ref:isil": "CH-001606-9"
  }
},
```
### Ways

The datatype way is an ordered list of nodes. It can either be an open way with a direction like a road or a closed way. For the latter the first and last node are the same making it a polygon. This can either be used to described an area or a structure like a building. A way can contain meta-data in tags like nodes can. But unlike nodes a way doesn't have coordinates. Instead it contains the IDs of its nodes, which can be empty except for their coordinates. To get the coordinates, the ID's have to be resolved and the coordinates extracted from the nodes. Quite some swiss libraries use the way object to draw the outline of their building. Which makes extracting this information necessary.

E.g. way:

```json
{
  "type": "way",
  "id": 180885479,
  "nodes": [
    1913371383,
    266864463,
    266864446,
    1913371376,
    1913371389,
    266864455,
    1913371381,
    1913371391,
    1913371386,
    1913371388,
    1913371392,
    1913371383
  ],
  "tags": {
    "addr:housenumber": "22",
    "addr:street": "Bettlistrasse",
    "amenity": "library",
    "building": "yes",
    "contact:phone": "+41 44 8018353",
    "contact:website": "http://www.duebendorf.ch/de/verwaltung/aemter/?amt_id=11029",
    "name": "Stadtbibliothek Dübendorf"
  }
}
```
E.g. first empty node of the way.

```json
{
  "type": "node",
  "id": 1913371383,
  "lat": 47.3983256,
  "lon": 8.6239376
}
```

### Relations

The third datatype are relations. They describe the relation between nodes, ways and other relations. For this they contain an ordered member list of their elements. They are a very versatile element of OSM and have different uses. In the context of buildings they can be used to map multi-polygons. E.g. buildings with several unconnected parts or containing courtyards. For the latter the members of a relation can have roles like inner or outer (walls). Relations don't have coordinates either, only the elements contained within them have. This requires resolving them to get the coordinates. Only two libraries in Switzerland currently use this datatype: The HSG library in St. Gallen an the library of the USI in Lugano.

E.g. 

```json
{
  "type": "relation",
  "id": 4567680,
  "members": [
    {
      "type": "way",
      "ref": 326918536,
      "role": "outer"
    },
    {
      "type": "way",
      "ref": 326918537,
      "role": "inner"
    }
  ],
  "tags": {
    "addr:city": "Lugano",
    "addr:country": "CH",
    "addr:housenumber": "13",
    "addr:postcode": "6900",
    "addr:street": "Via Giuseppe Buffi",
    "amenity": "library",
    "building": "yes",
    "contact:email": "library.lu@usi.ch",
    "contact:phone": "+41 58 666 45 00",
    "contact:website": "https://it.bul.sbu.usi.ch/",
    "name": "Biblioteca USI",
    "ref:isil": "CH-000342-4",
    "type": "multipolygon"
  }
}
```
## Coordinates
The output of osm-libch requires coordinates for use in small maps. For this osm-libch needs to resolve the ways and relations. Currently it's just planned to use the output to draw circles on small maps at a low zoom level. So for the moment osm-libch just returns the coordinates (longitude, latitude) of the first node for ways (polygons) and the first node of the first way for relations (multipolygons).  
In the future osm-libch should be improved by calculating the geometric center of the polygons. This would lead to more accurate results. be a much more accurate. For future uses an addition of 




