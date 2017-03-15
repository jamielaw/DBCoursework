# DBCoursework
* COMP3013/GC06: Database and Information Management Systems
Group 2's project for COMP3013. 
* Specification found here: https://moodle.ucl.ac.uk/pluginfile.php/261875/mod_resource/content/11/dB2CwkBrief%20SocialMedia.pdf
* Video demonstration of application:
https://www.youtube.com/watch?v=NY4UIKaGMB8

## Split into four main directories
### images
Contains images for user profiles and photo collections
### sn
Contains php files for social network pages (these php files also use sql for database manipulation). We also have a database.php file that all php files (that use sql) refer to in order to maintain credential consistency (dbName, dbHost, dbUsername, dbUserpassword).
### sql
Contains sql scripts (creating the database and respective tables, as well as populating/deleting them)
### loginTest
Contains login files for session manipulation
