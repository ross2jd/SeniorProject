###############################################################################
## update_genomics_portal_block_datasets.py
##
## This script parses the HTML from the Genomics Portal website and takes the
## dataset names and descriptions and updates the MySQL table,
## genomics_portal_block_datasets, with the parsed values. This script is meant
## to be run by a scheduled task on the server.
##
## Revision History
## jross    12/18/2013  Created.
## jross    12/20/2013  Added in the functionality to put in the GET string names
##
###############################################################################
__author__ = 'Jordan Ross'

import MySQLdb
import urllib2
from BeautifulSoup import BeautifulSoup

user_agent = 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-US) AppleWebKit/534.3 (KHTML, like Gecko) Chrome/6.0.472.63 Safari/534.3'
headers = { 'User-Agent' : user_agent }
req = urllib2.Request('http://www.eh3.uc.edu/GenomicsPortals/experiment.do', None, headers)
response = urllib2.urlopen(req)
page = response.read()
soup = BeautifulSoup(''.join(page))
portal_list = soup.findAll("td","ExperiPortalsTableCells")
# portal_list contains all the <td> and all content between them that make up the table of available portals.
# Now the entries with an anchor tag (<a>) will contain the portal names while the remaining entries will contain the
# description
portal_name_list = []
portal_description_list = []
portal_getstring_list = []

for portal in portal_list:
    # This will find the portal names and strip away any unwanted characters and append it to the list of portal names
    if portal.find("a") is not None:
        for string_port in portal.findAll(text=True):
            string_stripped = str(string_port).replace('\n', "").replace('\t', "")
            string_stripped = string_stripped.rstrip()
            if string_stripped is not "":
                portal_name_list.append(string_stripped)
    else:
        for string_desc in portal.findAll(text=True):
            string_stripped = str(string_desc).replace('\n', "").replace('\t', "")
            string_stripped = string_stripped.rstrip()
            if string_stripped is not "":
                portal_description_list.append(string_stripped)
    if portal.find(attrs={"name" : "portal_name"}) is not None:
        name = portal.find(attrs={"name" : "portal_name"})
        portal_getstring_list.append(str(name['value']).strip())
        # We want to value of the input tag with the name="portal_name" so we know the GET method values.

if len(portal_description_list) is not len(portal_name_list):
    # We have an error and should throw an exception
    print "The number of descriptions do not match the number of portal names!"

# Now we have the list of descriptions and the list of portal names that we can add into the database
# Comment out the db stuff because we cant test on desktop
db = MySQLdb.connect(host="127.0.0.1", port=3306, user="root", passwd="Hockey101", db="webbioblocks")
cursor = db.cursor()
sql = "TRUNCATE TABLE genomics_portal_block_datasets"
# We are going to be very simple and just delete all the entries in the table and re add them.
try:
    # Execute the SQL command
    cursor.execute(sql)
except:
    print "Error: unable to delete the table entries"

# Now repopulate the table
for i in range(0, len(portal_name_list)):
    sql = "INSERT INTO genomics_portal_block_datasets (portal_name, portal_getstring, description) VALUES('%s', '%s'," \
          " '%s')" % (portal_name_list[i], portal_getstring_list[i], portal_description_list[i])
    print sql
    try:
        # Execute the SQL command
        cursor.execute(sql)
        db.commit()
    except:
        print "Error: unable to add entry!"

db.close()
print "Done!"

