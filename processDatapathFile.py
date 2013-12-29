__author__ = 'Jordan Ross'

import os
import genomicsPortalBlock
import intersectBlock
import sys


def get_blocks_from_datapath_file(file_name):
    # Open the file for reading.
    fh = open(file_name, 'r')

    # Read all blocks into a list of dictionaries
    footer = "****"
    block = {}
    blocks = []
    for line in fh:
        line = line.strip('\n')  # Remove the new line characters
        if line == footer:
            # We have a block now so append the entire block to list of blocks
            blocks.append(block)
            block = {}
        else:
            components = line.split("^&^")
            block.update({components[0]: components[1]})

    fh.close()
    return blocks


def process_website_block(block):
    # First thing we should do is see what block it is
    if block['block'] == 'Genomics':
        # We have a genomics website block, we should call its respective function
        genomicsPortalBlock.process_block(block)
    else:
        # An unsupported block was found!
        print "Error:2:Unsupported block!"
        exit()
    # Return true since this block has completed
    return True


def process_logical_block(block, blocks):
    # First thing we should do is see what block it is
    if block['block'] == 'Intersect':
        # We have a genomics website block, we should call its respective function
        intersectBlock.process_block(block, blocks)
    else:
        # An unsupported block was found!
        print "Error:2:Unsupported block!"
        exit()
    # Return true since this block has completed
    return True


###############################################################################
#                                  MAIN                                       #
###############################################################################
if __name__ == '__main__':
    rel_path = sys.argv[1]
    script_dir = os.path.dirname(__file__)
    #rel_path = "datapathTemp/datapathBlocks1921681105.txt"
    abs_file_path = os.path.join(script_dir, rel_path)
    # Get the blocks from the datapath file into a list of dictionaries
    blocks = get_blocks_from_datapath_file(abs_file_path)
    # Now we are going to loop over each block in the list
    for block in blocks:
        # Check to see if this block is a website or logical block
        blockCategory = block['blockCat']
        if blockCategory == 'website':
            # The current block is a website block, process accordingly
            completed = process_website_block(block)
            block.update({'completed': completed})
        elif blockCategory == 'logical':
            # The current block is a logical block, process accordingly
            completed = process_logical_block(block, blocks)
            block.update({'completed': completed})
        else:
            # Unknown block
            print "Error:1:Unknown Block!"
            exit()

    print blocks