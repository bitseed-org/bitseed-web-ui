#!/usr/bin/env python
# ----------------------------------------------------------------------------
#  File - lin_rd_bconf_mbox.py
#
#  Written by:  Konn Danley
#  Date:        11/26/2015
#  Purpose:     This script resides in /home/linaro.  It reads the following
#               parameters from ./bitcoin/bitcoin.conf:
#                   * Max Connections (default 125)
#                   * Min Relay Tx Fee (default .00001000)
#                   * Limit Free Relay
#
# -----------------------------------------------------------------------------
import os
import subprocess
import json
import re

def parse_conf():

	# Bitcoin configuration defaults
    btc_dict_val_defaults = { "minrelaytxfee" : .00001000, "maxuploadtarget" : 1000, "maxmempool" : 300,
                         "disablewallet": 1, "listenonion" : 1, "onlynet" : 1, "upnp" : 1 }

	# Bitseed configuration defaults
    bts_dict_val_defaults = { "autoupdate" : 1, "disablebackups" : 0 }

    # ---------------------------------------------------------------------
    #  Read the required values out of .bitcoin/bitcoin.conf
    # ---------------------------------------------------------------------
    fh = open("./.bitcoin/bitcoin.conf", "r")
    lines = fh.readlines()
    fh.close()

    # ---------------------------------------------------------------------
    #  Read the required values out of .bitseed/bitseed.conf
    # ---------------------------------------------------------------------
    fh = open("./.bitseed/bitseed.conf", "r")
    bts_extend_lines = fh.readlines()
    fh.close()

	# This contains the superset of both bitcoin.conf as well as bitseed.conf
    lines.extend(bts_extend_lines)

    # Remove comments
    temp_lines = []
    for line in lines:
        line = line.partition('#')[0]
        line = line.rstrip()
        temp_lines.append(line)
		
    # Remove blank lines
    keep_lines = []
    for line in temp_lines:
        if line.strip():
            keep_lines.append(line)

    # ------------------------------------------------------------------
    # At this point, you have a list of key, value pairs through '='.
    # Extract the parameters of interest and build a Python dictionary 
	# which will be converted into a JSON
    # object to pass back to php to set the variables which will be 
	# used to populate the value fields of the input text boxes  
    # ------------------------------------------------------------------
    dict_params={}
    params_list = ["minrelaytxfee", "maxuploadtarget", "maxmempool", 
	               "disablewallet", "autoupdate", "listenonion", "onlynet", 
                   "upnp", "disablebackups"] 

    for line in keep_lines:
        key, value = line.split("=")	    
        if key in params_list: 
            dict_params[key] = value

    # ------------------------------------------------------------------
    # If the parameters of interest hare not in the bitcoin.conf file,
    # then write the default value corresponding to that parameter into
	# rd_bconf_mbox. 	
    # ------------------------------------------------------------------
    # Make a list of the keys found in the .bitcoin/bitcoin.conf file.  
    for param in params_list:
        if param not in params_list:
            dict_params[key] = btc_dict_val_defaults[param]
		
    json_params_object = json.dumps(dict_params)
    return json_params_object

# ----------------------------------------------------------------
#  Write the json object to the read mailbox and then rewrite the 
#  the value 2.  Cron will later detect this value run 
#  www_rd_bconf_mbox.php
# ----------------------------------------------------------------
# Read mailbox
json_params = parse_conf()
fh = open("./rd_bconf_mbox", "w") 
fh.write(json_params)
fh.close()

# rd_bconf_flag
fh = open ("./rd_bconf_flag", "w")
fh.write ("2")
fh.close()


