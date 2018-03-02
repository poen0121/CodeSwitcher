# CodeSwitcher
PHP Framework ( PHP >= 5.2.0 ) CLI,CGI
> About

	CodeSwitcher is a web application framework with intuitive development.

	We believe that development must be a simple, flexible development framework.

	CodeSwitcher is the code version control framework.

	Based on Model 2 MVC architecture.
	-----------------------------------------------------
	MVC :

		( Model ） - in models directory.

		( View ） - in templates directory.

		( Controller ） - in events directory.

> Learning Documents

    Please read `readme.php` document.

> Directory Structure

	1.core : Main program files directory.

	2.configs : Configs directory support version control mechanism.

	3.languages : Languages directory support version control mechanism.

	4.libraries : Developer libraries directory support version control mechanism.

	5.models : Models directory support version control mechanism.

	6.events : Events script directory relies on the version control mechanism.

	7.resources : Storage resources directory version control mechanism is optional.

	8.templates : Templates directory support version control mechanism.

	9.storage : System storage directory.

> Version Control - Revision Rule

 	[Main version number] . [Minor version number] . [Revision number]

	#Main version number:
	A major software updates for incremental , usually it refers to the time a major update function and interface has been a significant change.
	 
	#Minor version number:
	Software release new features , but does not significantly affect the entire software time increments.
	 
	#Revision number:
	Usually in the software have bug , bug fixes released incremented version.

	Example :
	Version : 0.0.0
	Version : 1.0.0
	Version : 1.0.1
	Version : 1.1.0
	Version : 2.0.0
	Version : 2.0.1
	Version : 2.1.0

> Begin Controler

	Load the script when the system begin.

	Control events begin program is executed before the script.

> Commit Controler

	Load the script when the system shut down.

	After the script executes the control events end program.

> Note Namespace

	System usage :

	use csl_mvc;
	use csl_error;
	use csl_func_arg;
	use csl_header;
	use csl_inspect;
	use csl_path;
	use csl_import;
	use csl_file;
	use csl_language;
	use csl_template;
	use csl_version;
	use csl_browser;
	use csl_time;
	use csl_debug;
