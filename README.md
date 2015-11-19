# SimpleForms
Easily insert form data into a database.

Please note that this script only works if both the table name and unique primary id have the same prefixed name. See below for example:

Table Name = user   WHERE  Unique Primary Id = user_id 

or 

Table Name = locations   WHERE   Unique Primary Id = locations_id

# Usage
Should be pretty easy to understand. The formslib file builds a function using the form name. From there, it runs the function which is kept inside the form_functions file. This is where most of the editing will be in determining what posted values are going to the database. I've set up an example function. After this, just pass it into the simple form class and it will take care of the rest.
