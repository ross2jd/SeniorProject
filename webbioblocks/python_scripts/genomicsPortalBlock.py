__author__ = 'Jordan Ross'

from selenium import webdriver
import urllib2
import time
from pyvirtualdisplay import Display
import os


def retrieve_data(block):
    # Create the virtual screen to use...
    display = Display(visible=0, size=(800, 600))
    display.start()

    # Go to the url that we are looking for.
    url = 'http://www.eh3.uc.edu/GenomicsPortals/differentialExpressionSetup.do?data_set='
    url += block['data_set']+'&db='+block['db']
    driver = webdriver.Firefox()
    # Go to the url (actually opening up the page here)
    driver.get(url)
    # Now lets fill in the form
    # First we need to look for the includeORexclude name on the form.
    includeORexclude_elements = driver.find_elements_by_name("includeORexclude")
    for element in includeORexclude_elements:
        value = element.get_attribute("value")
        if value == block['includeORexclude']:
            # We have the radio button we want to mark as "checked"
            element.click()

    # Next we need to traverse the filterchk names
    list_elements = driver.find_elements_by_css_selector("span.bullet")
    for element in list_elements:
        element.click()
    time.sleep(1)  # TODO: Not sure if this is needed or not
    # Make a list of the fliterchk that we have first.
    block['filterchk'] = str(block['filterchk']).split(",")
    filterchk_elements = driver.find_elements_by_name("filterchk")
    for element in filterchk_elements:
        value = element.get_attribute("value")
        if value in block['filterchk']:
            print value
            element.click()

    # Now we want to select the prop radio buttons (step 2)
    prop_elements = driver.find_elements_by_name("prop")
    for element in prop_elements:
        value = element.get_attribute("value")
        if value == block['prop']:
            element.click()

    # Next we need to select from the drop down menu
    ifCluster_element = driver.find_element_by_name("ifCluster")
    allOptions = ifCluster_element.find_elements_by_tag_name("option")
    for option in allOptions:
        value = option.get_attribute("value")
        if value == block['ifCluster']:
            option.click()

    # Then we need to enter in our text values for the p value and change cutoff.
    sigcutoff_element = driver.find_element_by_name("sigcutoff")
    # Since Genomics portal gives a value already we need to delete what is in there before we add our keys
    cur_value = sigcutoff_element.get_attribute("value")
    new_value = ""
    for i in range(0, len(str(cur_value))):
        new_value += "\b"
    new_value += block['sigcutoff']
    sigcutoff_element.send_keys(new_value)
    foldchange_element = driver.find_element_by_name("foldchange")
    cur_value = foldchange_element.get_attribute("value")
    new_value = ""
    for i in range(0, len(str(cur_value))):
        new_value += "\b"
    new_value += block['foldchange']
    foldchange_element.send_keys(new_value)

    # Lastly we need to find the Analyze button and click it
    input_tags = driver.find_elements_by_tag_name("input")
    for tag in input_tags:
        type = tag.get_attribute("type")
        value = tag.get_attribute("value")
        if type == "submit" and value == "Analyze":
            tag_to_click = tag
            break

    # We have to bre ak from the loop or else it will look for elements that dont exist!
    tag_to_click.click()

    # Now we are on the page that will display the data.
    # TODO: Put a better try case here for when data does not appear!
    try:
        if block['output'] == "raw_data":
            data_link = driver.find_element_by_partial_link_text("Tabular format (.xls)")
            download_url = data_link.get_attribute("href")
        elif block['output'] == "inter_tree_orig":
            data_link = driver.find_element_by_partial_link_text("Original Data")
            download_url = data_link.get_attribute("href")
        elif block['output'] == "inter_tree_center":
            data_link = driver.find_element_by_partial_link_text("Centered Data")
            download_url = data_link.get_attribute("href")
        else:
            print "Error:3:Unknown Output!"

    except:
        print "Error:7:No data!"
        driver.quit()
        exit()

    # Now we retrieve the data to a txt file.
    user_agent = 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-US) AppleWebKit/534.3 (KHTML, like Gecko) ' \
        'Chrome/6.0.472.63 Safari/534.3'
    headers = {'User-Agent': user_agent}
    req = urllib2.Request(download_url, None, headers)
    response = urllib2.urlopen(req)

    script_dir = os.path.dirname(__file__)
    data_path = block['dataFile']
    abs_file_path = os.path.join(script_dir, data_path)
    data = response.read()
    with open(abs_file_path, "wb") as code:
        code.write(data)
    code.close()

    # End the selenium webdriver
    driver.quit()

    # Now we make sure we stop the display
    display.stop()


def process_block(block):
    print "Starting processing of: " + block['BlockName'] + "..."
    # Initialize variables
    chainable = False
    block_completed = False

    # First step is to retrieve the data from the genomics website using the data in the block.
    retrieve_data(block)

    # Second step is to check and see if the data can be chained (i.e. other blocks can use the data)
    output = block['output']
    if output == 'raw_data':
        # This block is "chainable"
        chainable = True

    if chainable:
        # Now we need to save the data to a temporary location and mark this block as complete.
        block_completed = True
    else:
        print "Non chainable outputs are not working yet :("
        exit()
    print "Done processing block: " + block['BlockName']
    return block_completed


def get_column_from_raw_data(data_file, column):
    script_dir = os.path.dirname(__file__)
    abs_file_path = os.path.join(script_dir, data_file)
    fh = open(abs_file_path, 'r')
    header = fh.readline()
    data_rows = fh.readlines()
    fh.close()
    header = str(header).replace('"', '')
    header = header.lower()
    header_list = header.split("\t")  # each entry in the list is separated by a tab
    column = str(column).lower()
    row_index = header_list.index(column)
    return_data = []
    for data_row in data_rows:
        data_row = str(data_row).replace('"', '')
        data_list = data_row.split("\t")
        return_data.append(data_list[row_index])
    return return_data