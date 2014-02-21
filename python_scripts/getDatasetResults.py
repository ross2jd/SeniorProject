__author__ = 'Jordan Ross'

import urllib2
from bs4 import BeautifulSoup, Tag
import os
import sys


# Workaround to deal with unicode error
reload(sys)
sys.setdefaultencoding("utf-8")

datasetQueried = sys.argv[1]
url = "http://www.eh3.uc.edu/GenomicsPortals/dataSetSearch.do?portal_name="+datasetQueried+"&gene_list_selected=false"
# The second argument that will be passed in is a file ID that the website will use to read the created file
fileID = sys.argv[2]
user_agent = 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-US) AppleWebKit/534.3 (KHTML, like Gecko) Chrome/6.0.472.63 Safari/534.3'
headers = { 'User-Agent' : user_agent }
req = urllib2.Request(url, None, headers)
response = urllib2.urlopen(req)
page = response.read()
soup = BeautifulSoup(''.join(page))
dataset_table = soup.find("table", "SearchResultsTable")
if dataset_table is None:
    # We have most likely found (as far as I know a dataset that is not public)
    print "Error:1"
    exit()
# Before we remove all the buttons from the page we need to get the arguments from the javascript function
# beginAnalysisWithoutGeneList()
dataset_list = []
database_list = []
buttons = dataset_table.findAll('input')
for button in buttons:
    jsFunctionCall = str(button['onclick'])
    split_jsFunctionCall = jsFunctionCall.split('"')
    arg1 = split_jsFunctionCall[1]
    arg2 = split_jsFunctionCall[3]
    dataset_list.append(arg1)
    database_list.append(arg2)
    button_parent = button.findParent('td')
    button.extract()
    button_parent.extract()  # Get rid of the empty td tag

# Now we want to add in a radio button tag as the first cell in each row. We are going to put the database and dataset
# associated with the results in hidden tags so we can pass them via forms.
rows = dataset_table.findAll('tr')
subsoup = BeautifulSoup()
row_num = 0;
for row in rows:
    if row_num != 0:
        #new_cell = Tag(subsoup, "td")
	new_cell = subsoup.new_tag("td")
        #radio_button = Tag(subsoup, "input", [("type", "radio"), ("name", "radio_button")])
	radio_button = subsoup.new_tag("input")
	radio_button['type'] = "radio"
	radio_button['name'] = "radio_button"
        #dataset_hidden = Tag(subsoup, "input", [("type", "hidden"), ("name", "data_set"), ("value", dataset_list[row_num-1])])
        dataset_hidden = subsoup.new_tag("input")
	dataset_hidden['type'] = "hidden"
	dataset_hidden['name'] = "data_set"
	dataset_hidden['value'] = dataset_list[row_num-1]
	#database_hidden = Tag(subsoup, "input", [("type", "hidden"), ("name", "db"), ("value", database_list[row_num-1])])
        database_hidden = subsoup.new_tag("input")
	database_hidden['type'] = "hidden"
	database_hidden['name'] = "db"
	database_hidden['value'] = database_list[row_num-1]
	row.insert(0, new_cell)
        new_cell.insert(0, radio_button)
        new_cell.insert(1, dataset_hidden)
        new_cell.insert(2, database_hidden)
    else:
        # Enter in an empty cell tag to adjust the headers
        #new_cell = Tag(subsoup, "th")
	new_cell = subsoup.new_tag("th")
        row.insert(0, new_cell)
    row_num += 1

# Last thing we need to do is to give the table tag the class name for our CSS.
dataset_table['class'] = "datasetsTable"
fileName = 'genomicPortalTemp/test' + str(fileID) + '.html'
filePath = os.path.dirname(os.path.realpath(__file__))
tempFile = os.path.join(filePath, fileName)
fh = open(tempFile, 'w')
fh.write(dataset_table.prettify())
fh.close()
