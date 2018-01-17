# Data requirements for osm-libch output
### Based on [this specification file (in German)](Spezifikation_OSM_Datenstruktur_definitiv_Aktualisierung_31.10.2017.pdf) 

| KEY  		| VALUE | DATA TYPE  | EXAMPLE |  
|------		|--------|-----------|---------| 
|amenity	| library (required, selection criterium)| string | "library"  |
|name 		| library name | string | "Zentralbibliothek Zürich" | 
|operator |	library operator: company, institution (optional)| string | "Universität Zürich" |  
|addr:postcode | library postcode | integer (lenght: 4) | 8001 |
|addr:country | country: CH (as part of the address)| string | "CH"|
|addr:city 	| city, municipality | string | Zürich | 
|addr:street| street name (or house name: eg. Chateu) | string | "Chorgasse" | 
|addr:housenumber |	house number, stree number | string | "14a" | 
|contact:email  | library contact e-mail | string | info@example.com|
|contact:phone | library contact phone number (including swiss prefix: ++41)| string | "+41 (0) xx xxx xx xxxx"|	
|contact:website| library or institution homepage | string |  "https://www.zb.uzh.ch/" |
|ref:isil | International Standard Identifier for Libraries and Related Organizations (optional) | string | "CH-000008-6" |
|wikipedia | wikipedia page of the library (optional) | string | "fr:Bibliothèque de Genève"|
|wikidata | wikidata object of the library (optional) | string | "Q670848" |
|website | other important website related to the library: eg. repository, social media, blog ... (optional)| string | "http://biblio.arc.usi.ch" |	
| lat | latitude of the library | float | 46.5206483| 
| lon | longitude of the library | float | 6.5770502| 
| id | OSM ID of the library | int | 695849800 | 
| type | OSM Type of the library: node, way, relation (necessary for looking up the id on OSM) | int | way | 