All you need to do to get access to the functions listed below
is require 'Database/includes.php';

Note that Mentor.php and Mentee.php contain classes named Mentor and
Mentee. You will have to declare a Mentor and Mentee object to access
those functions. All other functions are global.

This is where you can find the functions for adding and removing
matches. They require a database which has been connected to as well
as the emails of both the mentee and mentor.
Database/Matching/matchFunctions.php
  addMatch($database,$mentorEmail,$menteeEmail)
  removeMatch($database,$mentorEmail,$menteeEmail)

Of great importance to me for the demo are the following three buttons:

"Import Old Database"
Database/ImportDatabase/importDatabaseFunctions.php
importOldDatabase($database,$convert,$oldDatabase)

$convert should just be the path to a python script we wrote
located at Database/ImportDatabase/convert.py

$oldDatabase is the path to a file which the user should
enter or pick from a file browser. It should have a .csv
extension (but that is not explicitly required).

"Import Database"
Database/ImportDatabase/importDatabaseFunctions.php
importDatabase($database,$file)

$file is the path to a file which the user should enter
or pick from a file browser. It should have a .csv
extension (but that is not explicitly required).

"Export Database"
Database/ExcelFunctions/excelFunctions.php
exportTablesToExcel($database, $tableNames,$filename)

For now just set $tableNames = ["Mentor","Mentee"]

$filename should be the name of the file to write the
data from the database. It should have a .csv extension.
$filename really should be entered by the user, but feel
free to just hardcode a value if you want. I'm not sure
how hard all this stuff is to do and I don't want to overload
the gui team.


It would also be nice to have "Remove Mentor" and "Remove Mentee" buttons
to call the following functions. They require a connected database and
the email of the mentor or mentee who should be removed.

Database/Mentor/Mentor.php
  removeMentor($database,$mentorEmail)

Database/Mentee/Mentee.php
  removeMentee($database,$mentorEmail)

Also useful would be "Clear Mentor" and "Clear Mentee" buttons which call
the following functions. They only need a connected database.
One thing to note about these is that they remove all the data from tables.
Probably a good idea to pop up an "Are you sure?" dialog box or something 
like that.

Database/Mentor/Mentor.php
  clear($database)

Database/Mentee/Mentee.php
  clear($database)

As of right now, none of these functions besides the import/export ones are actually written, but they will be
(I hope by Saturday night). I will definately be testing the buttons on Sunday
to make sure that they are functioning correctly.

I will let you know if I think there is a problem coming from the interface, but
most likely the buttons will be the least of my worries.

Send me an email if you have any questions or concerns.

Best,
Zane Rice <zaner@clemson.edu>
CPSC 472
