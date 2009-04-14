README.txt
==========

A module containing helper functions for Drupal developers and
inquisitive admins. This module can print a log of
all database queries for each page request at the bottom of each page. The
summary includes how many times each query was executed on a page
(shouldn't run same query multiple times), and how long each query
 took (short is good - use cache for complex queries).

Also dpr() and dpm() functions are provided, which pretty print arrays and strings. Useful during
development.

Included in this package is also: 
- devel_node_access module which prints out the node_access records for a given node._

Macro module has moved to http://drupal.org/project/macro.

Goodies
-------------
- You should also download Krumo from http://krumo.sourceforge.net/. Unpack it into a subdirectory 
called krumo. Devel will automatically start using it. You may also call krumo($variable) to get 
a pretty print of any variable.

AJAX developers in particular ought to install FirePHP Core from http://www.firephp.org/ and put it in the devel directory. Your path to fb.php should looks like devel/FirePHPCore/lib/FirePHPCore/fb.php. You can use svn checkout http://firephp.googlecode.com/svn/trunk/trunk/Libraries/FirePHPCore. Then you can log php variables to the Firebug console. Is quite useful. 



AUTHOR/MAINTAINER
======================
-moshe weitzman
weitzman at tejasa DOT com