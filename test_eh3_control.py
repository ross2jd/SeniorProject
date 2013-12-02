__author__ = 'Jordan Ross'

import urllib2
from selenium import webdriver
import time

user_agent = 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-US) AppleWebKit/534.3 (KHTML, like Gecko) Chrome/6.0.472.63 Safari/534.3'
headers = {'User-Agent' : user_agent}
url = 'http://www.eh3.uc.edu/GenomicsPortals/analysisWithoutGeneList.do?data_set=GSE3494Entrez&db=hgu133aEntrez'
req = urllib2.Request(url, None, headers)
response = urllib2.urlopen(req)
page = response.read()

# We want to try and simulate a button click.
driver = webdriver.Chrome()
driver.get("http://www.eh3.uc.edu/GenomicsPortals/analysisWithoutGeneList.do?data_set=GSE3494Entrez&db=hgu133aEntrez")
time.sleep(10)
driver.find_element_by_xpath("//*[@id='center_holder']/table/tbody/tr[2]/td[2]/table[3]/tbody/tr[1]/td[1]/input").click()
time.sleep(5)
url = driver.current_url
print url
driver.find_element_by_xpath("//*[@id='center_holder']/table/tbody/tr[2]/td[2]/div[3]/form/div[4]/div[1]/input").click()
#button_elements = driver.find_element_by_class_name("analysis-button").click()
