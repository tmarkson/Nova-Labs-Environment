#!/bin/python
#
# Title: queryThermostat.py
# Description: Requests status from a Filtrete CT-30 WiFi thermostat to a webserver using two HTTP GET actions
# Implementation: Run at some interval (using cron

import pycurl
import json
import cStringIO
import urllib
import socket

buffer = cStringIO.StringIO()

# Setup for curl command to request status from thermostat
c = pycurl.Curl()
c.setopt(pycurl.FOLLOWLOCATION,1)
c.setopt(pycurl.VERBOSE, False)
url = 'http://10.100.10.61/tstat'
c.setopt(c.URL,url)
c.setopt(c.WRITEFUNCTION, buffer.write)

# Perform curl command
c.perform()

# Get string from buffer
jsonStr = buffer.getvalue()
# Converts string to dict
jsonDict = json.loads(jsonStr)

# Prints thermostat status for local reporting
print jsonDict
print

# Parameters dict for variables being sent to web server
params = {
u'author': 'Nova Labs Router @ ' + socket.gethostbyname(socket.gethostname()),
u'day': jsonDict["time"]["day"],
u'hour': jsonDict["time"]["hour"],
u'minute': jsonDict["time"]["minute"],
}
# Remove time since we loaded it to @params manually
del jsonDict["time"]

# Put all the remaining attributes of the thermostat status into a text string like key=value
# Will be sent via HTTP GET request to web serv
for key in jsonDict:
    params[key] = jsonDict[key]

# Prepare to send the thermostat status to web server
k = pycurl.Curl()
url = 'http://lumisense.com/nova-labs/status/DoThermostat.php'
k.setopt(k.URL, (url + '?' + urllib.urlencode(params)))
# Send status attributes to web server
k.perform()