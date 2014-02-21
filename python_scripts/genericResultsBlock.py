__author__ = 'Jordan Ross'

import os


def write_data_to_file(data, file_name):
    script_dir = os.path.dirname(__file__)
    abs_file_path = os.path.join(script_dir, file_name)
    print data
    fh = open(abs_file_path, 'w')
    for item in data:
        fh.write("%s\n" % item)
    fh.close()
    return


def get_data_intersect_block(name, blocks):
    for block in blocks:
        if block['block'] == 'Intersect':
            if block['blockName0'] == name:
                # We have the block that we need.
                if block['result']:
                    # The result is ready!
                    return block['result']
                else:
                    return None
    return None


def process_block(results_block, blocks):
    # Get the type of the input for the results block.
    input_type = results_block['blockType']
    if input_type == 'Intersect':
        # Process according to interect input type
        data = get_data_intersect_block(results_block['blockName'], blocks)
        if not data:
            # We don't have data because the data is not ready!
            return False
        else:
            write_data_to_file(data, results_block['resultsFile'])

    return True