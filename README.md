Osebo
=====

Map developed in collaboration with UNICEF to display the status of different projects developed in Ghana interactively. There is a back end where administrators can edit or upload files with new data.

## Requirements

 * PHP version 5.2.0 or higher
 * PHP extension php_zip (to create the kmz files): http://www.php.net/manual/en/book.zip.php

## Installation

In order to install the Osebo mapping system, please follow these steps:

### Step 1: config.php

You should create a file called config.php, located in the root of the project, where you define some configuration variables. This files is intentionally NOT included and it is ignored through the .gitignore file. This is done to enable each project to easily pull and push changes and update from a live environment, without overwriting the configuration file. 

Your configuration file should include the following:

define ("URL", "http://www.example.com"); // Full URL of your site
define ("PATH", "/var/www/"); // path to your files
define ("SITENAME", "Osebo"); // Name of your site, will be placed in the title tag
define ("EMAIL", "info@example.com"); // Admin e-mail, to receive error reports

// These constants are used within a class; use const XX = yy format 
const SERVER = "localhost"; // MySQL server name
const USER = "my_user"; // MySQL user name
const PASSWORD = "secret"; // MySQL password
const DATABASE = "osebo"; // MySQL database name

### Step 2: create the databse

Create a MySQL database. Use utf8_unicode_ci encoding. 

After creating the database, load the table structure. Simply load the file load.sql and the appropriate table structure will be created.

### Step 3: create map layout

You will need to load the regions into the system. That is, you will need to indicate the areas and their coordinates, in order for the system to properly mark the areas that you want to highlight. You can work on a nation-wide scale, or you could tailor the system to operate on a municipal level. You will need to load these regions into the table "regions". 

### Step 4: add data

Once the regions have been loaded, users can start adding data to the system. This can be done through the web-interface. Open index.php and you will find the tools to add information. 

## Important details

In order to visualize the map, your website should be publicly accessible and the URL constant in config should be properly defined. Google only returns maps with the data on it if it can fetch the kmz file. 

## Want to contribute? 

Clone our project and push your changes back! Get in touch if you want to discuss other options to contribute. 

## Copyright and license

Copyright 2012-2013 IBIS Servicios

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this work except in compliance with the License.
You may obtain a copy of the License in the LICENSE file, or at:

  [http://www.apache.org/licenses/LICENSE-2.0](http://www.apache.org/licenses/LICENSE-2.0)

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
