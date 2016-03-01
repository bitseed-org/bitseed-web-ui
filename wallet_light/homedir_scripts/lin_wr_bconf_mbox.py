#!/usr/bin/env python
# --------------------------------------------------------------------
#  File - lin_wr_bconf_mbox.py
#
#  Written by:    Konn Danley
#  Date:          01/07/2016
#  Purpose:       This script resides in /home/linaro and is used 
#                 to transfer data from wr_bconf_mbox and merge these 
#                 values into .bitcoin/bitcoin.conf
# 
# --------------------------------------------------------------------
import os
import subprocess
import json
import re

btc_params_list = ["minrelaytxfee", "maxuploadtarget", "maxmempool", 
                   "disablewallet", "listenonion", "onlynet", "upnp"] 

bts_params_list = ["autoupdate", "disablebackups"] 

# The json file below has parameters from both bitcoin.conf and bitseed.conf
json_data=open('wr_bconf_mbox')
wr_mbox_dict_params = json.load(json_data)
param_keys = wr_mbox_dict_params.keys()

# Split wr_mbox_dict_params into btc_mbox_params and bts_mbox_params
btc_mbox_params = {}
bts_mbox_params = {}
for mbox_key in wr_mbox_dict_params:
    if mbox_key in btc_params_list:
        btc_mbox_params[mbox_key] = wr_mbox_dict_params[mbox_key]	
    if mbox_key in bts_params_list:
        bts_mbox_params[mbox_key] = wr_mbox_dict_params[mbox_key]
		
# -----------------------------------------------------------------------
# Processing for key/value pairs taken from bitcoin.conf and bitseed.conf
fh = open(".bitcoin/bitcoin.conf", "r"); 
btc_lines = fh.readlines() 
fh.close()

fh = open("./.bitseed/bitseed.conf", "r")
bts_lines = fh.readlines()
fh.close()

# ----------------------------------------------------------
# Create dictionaries from the bitcoin.conf and bitseed.conf
# files.
# ----------------------------------------------------------
bitcoin_conf_dict = {}
bitseed_conf_dict = {}
for line in btc_lines:

    # Skip blank lines
    temp_line = line.strip()
    if temp_line:

		# Skip comments
        if temp_line[0] == '#':
            pass
        else:
            line_key, line_value = temp_line.split("=")
            bitcoin_conf_dict[line_key] = line_value

for line in bts_lines:

    # Skip blank lines
    temp_line = line.strip()
    if temp_line:

		# Skip comments
        if temp_line[0] == '#':
            pass
        else:
            line_key, line_value = temp_line.split("=")
            bitseed_conf_dict[line_key] = line_value
# ----------------------------------------------------------

# ----------------------------------------------------------
#  Merge values from UI into bitcoin.conf lines array
# ----------------------------------------------------------
for mbox_key in bitcoin_conf_dict:
    if mbox_key in btc_params_list: 
        for i in range(len(btc_lines)):
            line = btc_lines[i]
            if line.strip(): 
                if line[0] == '#': 
                    pass
                else:
                    line_key, line_value = line.split("=") 
                    # replace value in bitcoin.conf
                    if line_key == mbox_key:
                        if line_key in btc_mbox_params:
                            temp_str = mbox_key + "=" + str(btc_mbox_params[mbox_key]) + "\n"
                            btc_lines[i] = temp_str 

# ----------------------------------------------------------
#  Merge values from UI into bitseed.conf lines array
# ----------------------------------------------------------
for mbox_key in bitseed_conf_dict:
    if mbox_key in bts_params_list: 
        for i in range(len(bts_lines)):
            line = bts_lines[i]
            if line.strip(): 
                if line[0] == '#': 
                    pass
                else:
                    line_key, line_value = line.split("=") 

                    # replace value in bitcoin.conf
                    if line_key == mbox_key:
                        if line_key in bts_mbox_params:
                            temp_str = mbox_key + "=" + str(bts_mbox_params[mbox_key]) + "\n"
                            bts_lines[i] = temp_str 

# Make a backup of the PREVIOUS bitcoin.conf file (bitcoin.conf.bak) 
subprocess.call (["cp", "./.bitcoin/bitcoin.conf", 
                  "./.bitcoin/bitcoin.conf.bak"]) 
# Make a backup of the PREVIOUS bitseed.conf (bitseed.conf.bak) 
subprocess.call (["cp", "./.bitseed/bitseed.conf", 
                  "./.bitseed/bitseed.conf.bak"])

# Write out the new bitcoin.conf file.
fh = open("./.bitcoin/bitcoin.conf", "w"); 
for i in range(len(btc_lines)):
    fh.write(btc_lines[i])
fh.write("\n")
fh.close()

# Write out the new bitseed.conf file.
fh = open("./.bitseed/bitseed.conf", "w"); 
for i in range(len(bts_lines)):
    fh.write(bts_lines[i])
fh.write("\n")
fh.close()
