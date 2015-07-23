#!/usr/bin/env python
import os
import subprocess
import json

def disk_status():

    # Disk drive -related logic
    command = "df -h /dev/sda1"
    proc = subprocess.Popen(command, stdout=subprocess.PIPE, shell=True)
    (out, err) = proc.communicate()
    s = out.split("\n")
    words = s[1].split()
    disk_size = words[1]
    disk_used = words[2]
    disk_avail = words[3]
    # ----------------------------------------------------------------------

    # RAM Usage
    command = "free -m"
    proc = subprocess.Popen(command, stdout=subprocess.PIPE, shell=True)
    (out, err) = proc.communicate()
    s = out.split("\n")
    words = s[2].split()
    ram_used = words[2] + " MB"
    ram_free = words[3] + " MB"
    # ----------------------------------------------------------------------

    # CPU Load (1, 5, 15 minute averages)
    command = "uptime"
    proc = subprocess.Popen(command, stdout=subprocess.PIPE, shell=True)
    (out, err) = proc.communicate()
    s = out.split("\n")
    load_line = s[0].split()    
    load_line = load_line[-3:]
    load = " ".join(load_line)
    # ----------------------------------------------------------------------

    # Uptime 
    command = "uptime -p"
    proc = subprocess.Popen(command, stdout=subprocess.PIPE, shell=True)
    (out, err) = proc.communicate()
    s = out.split("\n")
    uptime_line = s[0].split()
    uptime_line = uptime_line[1:]
    uptime = " ".join(uptime_line) 
    # ----------------------------------------------------------------------

    # Create a dict for all the paramters and encode into json format
    return_values = {"disk_size": disk_size, "disk_used": disk_used, "disk_avail": disk_avail,
	                 "ram_used": ram_used, "ram_free": ram_free, "load": load, "uptime": uptime}
    json_values = json.dumps(return_values)
    return json_values

s = disk_status()
print s
