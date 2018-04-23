# AWD (All Wheel Drive) Web - Php Version (2.0)
This is the php / apache version of the All Wheel Drive framework I've picked at for the last decade.  I created it when friends or individuals wanted a quick website.  I've rewritten it a little so its smaller, but below goes over all the areas you could adjust to use yourself. 

## Getting Started

### Installing

You will need to have an apache server running php 5.4 or above.  After that download, and start using.  The next sections will go over the areas you can adjust

## Using the Framework

### Url framework

We will start on the structure a url must be in. This framework sends a folder “/” delimited list of variables to the index.php page based on the url.  If the file is a .css, .js, or a known images, it loads the content.  Besides that, it will send variables to the index.php page of the root.  Below are the specific variables looked for at each index.

array -> 
	0 - page, (1st folder /)
	1 - type, (2nd folder /)
	2 - uri_arr, (all folders / past) (considered the querystring)

If the “type” variable isn’t a known html, json, xml, or api, it will shift the indexes up so the page variable is index 0.

Note: also in that index.php file you can change the environment to anything other than prod, and also the folder directory for the framework to another location.  Currently it is just a relative “awd” folder page

### Awd framework file

The only file the index.php includes directly is the awd_framework.php file.  This file generates a global array $AWD object with all the settings, includes the needed config.php and function.php files, and loads the correct layout.  It will also catch any global errors that are thrown as Exceptions.  I’ll now go over each of the three main areas mentioned in the awd framework file next.

### $AWD Global Array

This is the main global setting arrays.  There are others set and thrown around, but this should be the only one you want to use.  Because those others might have other items that overwrite them.  $AWD_CONFIG is an example, and i’ll reference it in many of the sections.  I’ll mention more in the config and function load section next.  The $AWD array consists of this structure.

AWD array -> 
	0 - type, (1st folder /)
	1 - page, (2nd folder /)
	2 - uri_arr, (all folders /)
	3 - uri,
	4 - page_variables, (all folders besides 1st and 2nd)
	5 - request_type (used for api, but will be null for everything else)

Note: I am planning on making this more of a class object letter, but right now it is still just an array.

### Config.php and Function.php files

The Framework folder (awd objects), the userbuilt folder (where you can add objects), and the actual template folders under the layout folder of this structure can have a config.php and a function.php file.  Under the framework folder, the config and function are for everything.  Config also does the magic method to load classes dynamically as well as sets all the major global objects.  Below are a list of those global objects

Defined Constants

	AWD_PAGEEVENTS_PATH -> page event file paths. Check out the page events section for details. By default its the event folder under the root.
	AWD_LAYOUTS_PATH -> location for layout templates. Check out the layouts section for details. By default its the layouts folder under the root.
	AWD_HTML_SCRIPTS_PATH -> location for all javascript scripts. By default its scripts under the root.
	AWD_HTML_CSS_PATH -> location for all javascript scripts. By default its a css folder under the selected layout template.
	AWD_FRAMEWORK_PATH -> location for all framework extensions.  It works with the AWD_DIR set in the main index file.
	AWD_USERBUILT_PATH -> location for all userbuilt extensions.  It works with the AWD_DIR set in the main index file.
	AWD_PAGES_PATH -> page file paths.  This is loaded in the layout class.  By default it is the page folder under the root.
	AWD_BASEURL -> quick reference for $AWD_CONFIG [‘url’][‘base’]
	AWD_COOKIEURL -> quick reference for $AWD_CONFIG [‘url’][cookie]

Functions

	awd_main_array() -> this returns the $AWD array in the main php file.
	awd_istype($type -> this tests if the passed variable is a known awd type
	awd_gettype_fromheader() -> this tests if the request wants a json or xml instead of html
	awd_querystring() -> quick function to get the extra variables from the url
	awd_write_cssframework_tags() -> this writes layout css if you want it in the header.php
	awd_write_metadata() -> this writes all meta tags if wanted in the header.php
	awd_redirect_tosecure() -> this tests and redirects to https if needed
	awd_conn_int() -> this initiates the connection variable set.  By default it is mySQL. Its the conn array under the AWD_CONFIG global.
	awd_conn_injectclass() -> this will inject the database connection in a class that inherits the Data\Table or Data\Row
	awd_conn_close() -> global clean up for conn
	awd_is_custompageload($page, $type) -> there is a iPageLoad interface.  If you set in the $AWD_CONFIG array (key: custompageload) it will try and create that object when called
	awd_write_apirequest($type, $class, $request_type) -> this tries to load an api class by request.  Check out the GetPage Folder Structure and Functions section
	array_to_xml($array, $xml_root_str, $docType) -> this will change an array to an xml string.
	array_to_json($array) -> this will change an array to an json string.
	get_request_var($name) -> this will utilize the $POST php object
	is_mobile() -> this will return true or false if the browser is mobile.
	is_secure() -> this will return true or false if its an ssl.

Config and function files under the userbuilt and template folders can either replace settings / global functions, or add new ones.  Urls, database connection usernames so on are here.  Its just as needed.  Here is an example of the $AWD_CONFIG array in a config.php file

```
$AWD_CONFIG = array(
	"db" => array(
	  0 => array(
		"dbname" => "TEST", 
		"username" => "FOO", 
		"password" => "BAR", 
		"servername" => "http://TEST.FOO.BAR"
	  )
	),
	"url" => array(
		"base" => "http://localhost",
		"private" => "https://localhost",
		"cookie" => "localhost"
	), 
	"path" => array(
		"scripts" => "scripts",
		"events" => "events",
		"pages" => "pages",
		"layouts" => "layouts",
		"resources" => array(
			"framework" =>  "/framework",
			"userbuilt" =>  "/userbuilt",
			"upload" => "/upload",
			"framework_pages" => "/framework/pages"
		)
	),
	"selectedlayout" => "blank",
	"defaultpage" => "home",
	"conn" => array(
		"useiconnection" => true,
		"iconnectionclass" => "\AWD\Data\MySQL\Connection"
	)
);
```

NOTE: this is another process i want to eventually refactor to php classes.

### Layout Class

The layout class is the one called by the awd_frameworks.php to render the needed page.  It does this by type and page.  It calls the static method render that executes any event files in order, and loads the header, footer, and page content.  The events and page structure we’ll go over in the next two sections.

The header and footer go off the selected template.  That is defined by the “selectedlayout” key under $AWD_CONFIG.  This will look for that specific folder under the layouts path.  The structure will be a header.php, footer.php, and functions.php file if present any are present. There is a blank template example if you want to take a quick look at it. You can add as many similar folders as you want. Just match the structure, and adjust the “selectedlayout” key in the config.

Note: only loads header and footer currently if it is an “html” type.

Note: If you look at the code, you’ll see that it checks if an awd framework was called directly.  No need to worry about that.  At this time, this repository doesn’t utilize that step so just skip to avoid confusion. The layout is what gets the page going. 

### GetPage Folder Structure and Functions

This page can get a little tricky under the Layout class.  I did this over the years as needs came up.  Ideally i wanted the api to be in the same place, and that is where this started.  It first goes off the page folder path defined in $AWD_CONFIG, and tries type specific.  This would be the type passed like html, json, or xml.  Here is the structure with {} as variables.  
```
{page path}/type_only/{type}"_pages/{page}.php
```
If not exists, then the next step it tries to find the page in the root of the page path.  Note: that can only by the html type for later reasons.

If not exists, it tries to load the api structure by calling awd_write_apirequest().  This will go into more detail under the All Other Framework and Classes section, but basically this looks for objects that have Api interfaces implemented, and if that class name is one, it loads and serializes the data out.

Next, if those don’t find anything, it tries the awd_is_custompageload() function.

Last, it shows a request can’t complete message.

### Page Event Class and File Structure

Growing up with microsoft page events, I added this layer to the structure.  There are currently only three event types:  preload, load, and postload.  Preload happens before any rendering.  Load happens before the page content loads, and postload happens after render.  These events can be achieved by doing the following.

Locate the event folder.  Now it depends on the specific page and type you want to add the event for.  Similar to the page folder structure. Here is the structure with {} as variables.

If exists,
```
{event path}/{event type}/type_only/{type}_pages/{page}.php
```
If that didn’t exist, it now checks
```
{event path}/{event type}/{page}.php
```
There is also an event we call always.  This is checked on every event type folder, but is similar to the above

If exists,
```
{event path}/{event type}/type_only/{type}_pages/always.php
```
If that didn’t exist, it now checks
```
{event path}/{event type}/always.php
```
NOTE: it will execute those events on ANY GetPage() type mentioned above.

### All Other Framework Classes

I’ll get more detail in this section as I get time to help with the code.  Right now dive into the php, and if you have any questions feel free to contact me. Now days with kids i don’t get a lot of time, but more than happy to help expand this if you have questions, or a better approach you would suggest. 

I went over some of the main classes (layout and page events).  There are two more before i dive into the sub sections.  That is the object class.  This class, has similar logic to microsoft where i want everything… well most everything to use it.  It dynamically loads properties and data, and also will serialize xml or json if certain functions are called.  Now for the sections

Api, is first an abstract that a class must inherit to be a part of it, and then a folder/namespace of classes that help with the request navigation.

Autho, is a folder/namespace that does right now just the basic one, but i’ll add my Oauth and Digest objects later when i get time.

Data, is a folder/namespace, and is a big one.  The Row and PageRow objects inherit the Api namespace.  Ideally, if you want to load data from a mySQL database for example… right now thats all i have cause I haven’t written to another source :) .  you will create a custom class, and inherit a row if its a single record, or a pagerow if its more than one.  You then define the datatable, connection srouce, and then you are in business. This is the big reason i started on the structure besides the default html layout this will allow your api and actual website html to be together.  I’ll go into more detail next as to where your created classes can go.

Exceptions, is a folder/namespace that consists of all custom exceptions for the framework.

Interfaces, is a folder/namespace with all the implementation class information. 

### Userbuilt Classes

I mentioned before the userbuilt folder.  In that folder, you can have a config.php file and a functions.php file that both create or override global settings / functions.  You can also create a “classes” folder and the framework will load objects for use also.  The framework class are all wrapped with the “\AWD” root namespace.  So i would recommend staying away from that here.  The individual file needs to class.{class lowercase name}.php.  Here is an example:

file contents:
```
class MyClass{

}
```
file:
class.myclass.php

Next, you can do a namespace hierarchy by adding a folder with the file in it.  Here is an example:

file contents:
```
namespace MyNamespace{
  class MyClass{

  }
}
```
path:
mynamespace\class.myclass.php

### Conclusion

I hope all of this helps in getting started.  Feel free to reach out if you have an comments are questions.  I mostly do apache / php on the side of my normal work.  So, doubt its the best in the world, but I hated using things like wordpress so came up with this as a quick way to add a base, and then start creating like javascript to call the api, classes and so on.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
