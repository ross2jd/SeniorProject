from selenium import webdriver
from selenium.common.exceptions import TimeoutException
from selenium.webdriver.support.ui import WebDriverWait # available since 2.4.0
from selenium.webdriver.support import expected_conditions as EC # available since 2.26.0
from selenium.webdriver.support.ui import Select
import urllib2
from bs4 import BeautifulSoup, Tag
import sys

# Ensure that we have the correct encoding on the file.
reload(sys)
sys.setdefaultencoding("utf-8")

# Create a new instance of the Firefox driver

url = 'http://david.abcc.ncifcrf.gov/tools.jsp'
driver = webdriver.Firefox()
driver.get(url)
# find the element that's name attribute is q (the google search box)
upload_anchor = driver.find_elements_by_partial_link_text("Upload")
upload_anchor[0].click()

paste_box = driver.find_element_by_name("pasteBox")
data = "1007_s_at\n1053_at\n117_at\n121_at\n1255_g_at\n1294_at\n1316_at\n1320_at\n1405_i_at\n1431_at\n1438_at\n1487_at\n1494_f_at\n1598_g_at"
paste_box.send_keys(data)

identifier_select = Select(driver.find_element_by_name("Identifier"))
identifier_select.select_by_value("AFFYMETRIX_3PRIME_IVT_ID")

list_type_radio = driver.find_elements_by_name("rbUploadType")
for element in list_type_radio:
    value = element.get_attribute("value")
    if value == 'list':
        element.click()
        break

submit_button = driver.find_element_by_name("B52")
submit_button.click()

result_url = driver.current_url
user_agent = 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-US) AppleWebKit/534.3 (KHTML, like Gecko) ' \
    'Chrome/6.0.472.63 Safari/534.3'
headers = {'User-Agent': user_agent}
req = urllib2.Request(url, None, headers)
response = urllib2.urlopen(req)
page = response.read()
soup = BeautifulSoup(''.join(page))
fh = open("test.html", "w")
fh.write(soup.prettify())
fh.close()

#driver.quit()
# # type in the search
# inputElement.send_keys("cheese!")
#
# # submit the form (although google automatically searches now without submitting)
# inputElement.submit()
#
# # the page is ajaxy so the title is originally this:
# print driver.title
#
# try:
#     # we have to wait for the page to refresh, the last thing that seems to be updated is the title
#     WebDriverWait(driver, 10).until(EC.title_contains("cheese!"))
#
#     # You should see "cheese! - Google Search"
#     print driver.title
#
# finally:
#     driver.quit()
#
# # Now we make sure we stop the display
# display.stop()

