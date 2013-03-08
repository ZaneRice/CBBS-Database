#!/usr/bin/python
#
# This python script converts the old database's single table into
# two tables usable in the new database.
#
# The script expects input from stdin and outputs the data into two
# files : Mentor.csv and Mentee.csv which represent the Mentor and Mentee
# tables in the new database.
#
# The input must be a .csv file. The easiest way to obtain this file is to
# use the 'save as' functionality in Excel to resave the old database's data.
#
# The Mentor.csv and Mentee.csv files can be loaded into the new database
# using the phpMyAdmin 'import' facility
#

import sys

def addToMentorTable(oldarr, mentorfile):
	newarr = []
	newarr.append(oldarr[0])   #Email
	newarr.append(oldarr[1])   #First
	newarr.append(oldarr[2])   #Middle
	newarr.append(oldarr[3])   #Last
	newarr.append(oldarr[4])   #Work Phone
	newarr.append(oldarr[5])   #Work Phone Ext.
	newarr.append(oldarr[6])   #Mobile Phone
	newarr.append(" ")         #Street Address
	newarr.append(" ")         #City
	newarr.append(" ")         #State
	newarr.append(" ")         #Country
	newarr.append(" ")         #Zip
	newarr.append("1")         #IsMentor

	if oldarr[12] == "TRUE":
		newarr.append("1") #IsMentee
	else:
	    	newarr.append("0") #IsMentee

	newarr.append("0")         #IsAdmin
	if oldarr[14] == "TRUE":
		newarr.append("1") #IsSponsor
	else:
	    	newarr.append("0") #IsSponsor
	newarr.append(oldarr[15])  #Years In Current Position
	newarr.append(oldarr[16])  #Years In Company
	newarr.append(" ")         #Company Name
	newarr.append(" ")         #Position
	
	if oldarr[17] == "TRUE":
		newarr.append("1") #ShowInMatchResult
	else:
	    	newarr.append("0") #ShowInMatchResult

	newarr.append(oldarr[18])  #Title
	newarr.append(oldarr[20])  #Background

	lineout = ','.join(newarr)
	mentorfile.write(lineout)
	mentorfile.write('\n')


def addToMenteeTable(oldarr, menteefile):
	#newarr = [" "] * 
	newarr = []
	newarr.append(oldarr[0])   #Email
	newarr.append(" ")         #University
	newarr.append(" ")         #Graduation Year
	newarr.append(" ")         #Major
	newarr.append(oldarr[1])   #First
	newarr.append(oldarr[2])   #Middle
	newarr.append(oldarr[3])   #Last
	newarr.append(oldarr[6])   #Mobile Phone
	newarr.append(" ")         #Street Address
	newarr.append(" ")         #City
	newarr.append(" ")         #State
	newarr.append(" ")         #Country
	newarr.append(" ")         #Zip
	
	if oldarr[11] == "TRUE":
		newarr.append("1") #IsMentor
	else:
	    	newarr.append("0") #IsMentor
	
	newarr.append("1")         #IsMentee
	newarr.append("0")         #IsAdmin
	
	if oldarr[17] == "TRUE":
		newarr.append("1") #ShowInMatchResult
	else:
	    	newarr.append("0") #ShowInMatchResult
	newarr.append(oldarr[20])  #Background

	lineout = ','.join(newarr)
	menteefile.write(lineout)
	menteefile.write('\n')

mentorfile = open("Mentor.csv", 'w')
menteefile = open("Mentee.csv", 'w')

while True:
	line = sys.stdin.readline()

	if not line:
	    menteefile.close()
	    mentorfile.close()
	    break

	linearr = line.split(',')

	if linearr[11] == "TRUE":
	    addToMentorTable(linearr, mentorfile)

	if linearr[12] == "TRUE":
	    addToMenteeTable(linearr, menteefile)
