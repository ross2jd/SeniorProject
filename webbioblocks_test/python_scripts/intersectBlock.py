__author__ = 'Jordan Ross'

import genomicsPortalBlock


def intersect_two_lists(list1, list2):
    # TODO: Optimize this!
    inter_list = []
    for item in list1:
        if item in list2:
            inter_list.append(item)
    return inter_list


def intersect_data_genomics_portal(data_files, criteria):
    data = []
    for data_file in data_files:
        # For the genomics portal block we are going to get the column named by the criteria (i.e. ID)
        data.append(genomicsPortalBlock.get_column_from_raw_data(data_file, criteria))
    for i in range(0, (len(data)-1)):
        data[i+1] = intersect_two_lists(data[i], data[i+1])

    # Return the intersected list
    return data[-1]


def intersect_data(intersect_block, data_files):
    # Get the criteria for the intersection
    criteria = intersect_block['criteria']

    # We want to get the data from the data file based on the criteria
    if intersect_block['blockType'] == 'Genomics Portal Block':
        data = intersect_data_genomics_portal(data_files, criteria)
    else:
        data = 0

    return data


def process_block(intersect_block, blocks):
    print "Starting processing of: " + intersect_block['blockName0'] + "..."
    block_names = []
    for i in range(1, int(intersect_block['numInputs'])+1):
        block_names.append(intersect_block['blockName'+str(i)])

    blocks_waiting = block_names
    data_files = []
    # We want to cycle through all the website blocks and check and see which ones are completed.
    for block in blocks:
        if block['blockCat'] == 'website':
            if block['block'] == 'Genomics':
                if block['BlockName'] in block_names:
                    # This is one of the inputs we want to check and see if it is completed
                    # Remove the block with this name from the blocks waiting list
                    blocks_waiting.pop(blocks_waiting.index(block['BlockName']))
                    # Get the data file path
                    data_files.append(block['dataFile'])
    if not blocks_waiting:
        # All of our blocks have completed (i.e the data is available)
        data = intersect_data(intersect_block, data_files)
        intersect_block.update({'result': data})
    else:
        return False

    print "Done processing block: " + intersect_block['blockName0']
    return True
