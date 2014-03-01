__author__ = 'Jordan Ross'

import os


def get_input_block(blockInputName, blocks):
    # Search the blocks for the block with the name
    for inputBlock in blocks:
        if inputBlock['block'] == 'Intersect':
            if inputBlock['blockName0'] == blockInputName:
                return inputBlock
        elif inputBlock['block'] == 'Genomics':
            if inputBlock['BlockName'] == blockInputName:
                return inputBlock
    print("Error:4:Unsupported input block for DAVID!")
    exit()


def process_block(block, blocks):
    print "Starting processing of: " + block['BlockName'] + "..."
    url = "http://david.abcc.ncifcrf.gov/api.jsp?"
    blockInputName = block['blockInput']
    # Get the input block
    inputBlock = get_input_block(blockInputName, blocks)
    if inputBlock['block'] == 'Intersect':
        data = inputBlock['result']
        if len(data) < 30:
            # We are going to use the API for the intersect block
            url += "type="+block['identifier']+"&ids="
            for id_data in data:
                url += id_data+","
            url += "&tool=gene2gene"
            file_name = block['dataFile']
            script_dir = os.path.dirname(__file__)
            abs_file_path = os.path.join(script_dir, file_name)
            fh = open(abs_file_path, 'w')
            fh.write("%s\n" % url)
            fh.close()
        else:
            # We need to use the selenium webdriver
            print("Error:5:To many genes! -- Use the generic result block and paste list into DAVID website")

    print "Done processing block: " + block['BlockName']


