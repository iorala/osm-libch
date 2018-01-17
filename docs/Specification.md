# Specification
## Summary
This software is a server-side program which enables the download of OpenStreetMap (OSM) data and makes it possible to convert the data resulting in a transmission of the relevant data to the target. The required data consists of details related to swiss libraries.

## Details
+ The program enables to poll requests through an application program interface (API). The request holds data related to swiss libraries which are classified according to their canton.
+ The program filters the data resulting in only the desired fields being sent to the target.
+ Different ways of recording libraries in OSM such as ways and nodes will be taken into consideration so that all libraries are included.

## Structure
The program comprises two parts:
+ Part 1: Script to fetch and import into PHP.
+ Part 2: Script to fetch and filter the export.
