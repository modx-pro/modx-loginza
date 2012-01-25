--------------------
Loginza
--------------------
Version: 1.0.0 pl
Author: Vasiliy Naumkin <bezumkin@yandex.ru>
--------------------

Loginza is an identification system that provides a single method of logging into popular web-services.

Feel free to suggest ideas/improvements/bugs on GitHub:
http://github.com/bezumkin/modx-loginza/issues



Install it from MODX package managment

Run: [[!Loginza]]

---------------------
Parameters:

&rememberme - remember the user. By default - true. Depends on the system variable session_cookie_lifetime
&loginTpl - Chunk to an unauthorized user. Default - tpl.Loginza.login
&logoutTpl - Chunk to an authorized user. Default - tpl.Loginza.logout
&saltName - salt for a username hash. Default - none.
&saltPass - salt to the password hash. Default - none.
&groups - in which groups to add the user at the first authorization, separated by commas. Default - none.
&loginContext - in what context login. Default - in the current. Not tested.
&addContexts - additional contexts, separated by commas. Default - none. Not tested.
