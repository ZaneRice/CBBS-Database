import sys

def addMentorHeaders(mentorfile):
	newarr = []
	newarr.append("Email")
	newarr.append("First")
	newarr.append("Middle")
	newarr.append("Last")
	newarr.append("WorkPhone")
	newarr.append("WorkPhoneExt")
	newarr.append("MobilePhone")
	newarr.append("StreetAddress")
	newarr.append("City")
	newarr.append("State")
	newarr.append("Country")
	newarr.append("Zip")
	newarr.append("IsMentor")
	newarr.append("IsMentee")
	newarr.append("IsAdmin")
        newarr.append("IsSponsor")
	newarr.append("YearsInCurrentPosition")
	newarr.append("YearsInCompany")
	newarr.append("CompanyName")
	newarr.append("Position")
	newarr.append("ShowInMatchResult")
	newarr.append("Title")
	newarr.append("Background")

	lineout = ','.join(newarr)
	mentorfile.write(lineout)
	mentorfile.write("\n")

def addMenteeHeaders(menteefile):
	newarr = []
	newarr.append("Email")
	newarr.append("University")
	newarr.append("GraduationYear")
	newarr.append("Major")
	newarr.append("First")
	newarr.append("Middle")
	newarr.append("Last")
	newarr.append("MobilePhone")
	newarr.append("StreetAddress")
	newarr.append("City")
	newarr.append("State")
	newarr.append("Country")
	newarr.append("Zip")
	newarr.append("IsMentor")
	newarr.append("IsMentee")
	newarr.append("IsAdmin")
	newarr.append("ShowInMatchResult")
	newarr.append("Background")
	
	lineout = ','.join(newarr)
	menteefile.write(lineout)
	menteefile.write("\n")


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
	newarr.append(oldarr[11])  #IsMentor
	newarr.append(oldarr[12])  #IsMentee
	newarr.append("FALSE")     #IsAdmin
	newarr.append(oldarr[14])  #IsSponsor
	newarr.append(oldarr[15])  #Years In Current Position
	newarr.append(oldarr[16])  #Years In Company
	newarr.append(" ")         #Company Name
	newarr.append(" ")         #Position
	newarr.append(oldarr[17])  #ShowInMatchResult
	newarr.append(oldarr[18])  #Title
	newarr.append(oldarr[20])  #Background

	lineout = ','.join(newarr)
	mentorfile.write(lineout)
	mentorfile.write("\n")


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
	newarr.append(oldarr[11])  #IsMentor
	newarr.append(oldarr[12])  #IsMentee
	newarr.append("FALSE")     #IsAdmin
	newarr.append(oldarr[17])  #ShowInMatchResult
	newarr.append(oldarr[20])  #Background

	lineout = ','.join(newarr)
	menteefile.write(lineout)
	menteefile.write("\n")


     
	


mentorfile = open("Mentor.csv", 'w')
menteefile = open("Mentee.csv", 'w')

addMentorHeaders(mentorfile)
addMenteeHeaders(menteefile)

while True:

	line = sys.stdin.readline()

	if not line:
		break

	#print line

	linearr = line.split(',')
	#print linearr #0 indexed

	if linearr[11] == "TRUE":
		addToMentorTable(linearr, mentorfile)

	if linearr[12] == "TRUE":
		addToMenteeTable(linearr, menteefile)

	lineout = ','.join(linearr)
	print lineout
