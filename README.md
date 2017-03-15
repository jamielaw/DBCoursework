# DBCoursework
* COMP3013/GC06: Database and Information Management Systems
Group 2's project for COMP3013. 
* Specification found here: https://moodle.ucl.ac.uk/pluginfile.php/261875/mod_resource/content/11/dB2CwkBrief%20SocialMedia.pdf
* Video demonstration of application:
https://www.youtube.com/watch?v=NY4UIKaGMB8

## Split into three main directories
### images
Contains images for user profiles and photo collections
### sn
Contains php files for social network pages (these php files also use sql for database manipulation). We also have a database.php file that all php files (that use sql) refer to in order to maintain credential consistency (dbName, dbHost, dbUsername, dbUserpassword).
Also contains login sessioning (see index.php). Folders listed below:
* admin - Contains php files for admin interface
* blog - Contains php files for blog interface
* circles - Contains php files for circle interface
* css - Contains bootstrap css files that are included in some files (this folder is mostly deprecated now as we use external src for most files)
* inc - Contains php files that are included in other pages, such as navbar
* photos - Contains php files for photo interface
* profile - Contains php files for profile interface
* search - Contains php files for search results page
* session - Contains logging in and logging out php files
* XML - Contains any XML exports and php scripts for importing/exporting XML
### sql
Contains sql scripts (creating the database and respective tables, as well as populating/deleting them)
