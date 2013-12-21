__author__ = 'Jordan Ross'

import urllib2
from BeautifulSoup import BeautifulSoup, Tag
import sys
import os

datasetQueried = sys.argv[1]
dbQueried = sys.argv[2]
fileID = sys.argv[3]
url = "http://www.eh3.uc.edu/GenomicsPortals/differentialExpressionSetup.do?data_set="+datasetQueried+"&db="+dbQueried

user_agent = 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-US) AppleWebKit/534.3 (KHTML, like Gecko) ' \
    'Chrome/6.0.472.63 Safari/534.3'
headers = {'User-Agent': user_agent}
req = urllib2.Request(url, None, headers)
response = urllib2.urlopen(req)
page = response.read()
soup = BeautifulSoup(''.join(page))
form = soup.find(attrs={"action": "differentialExpression.do"})

# Now we need to remove the analyze button at the bottom of the page.
analyzeButtonTags = form.findAll("div", "horizontal-left-float")
for analyzeButtonTag in analyzeButtonTags:
    analyzeButtonTag.extract()

fileName = 'genomicPortalTemp/test' + str(fileID) + '.html'
filePath = os.path.dirname(os.path.realpath(__file__))
tempFile = os.path.join(filePath, fileName)
fh = open(tempFile, 'w')
fh.write(form.prettify())
fh.close()