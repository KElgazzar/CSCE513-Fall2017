#!/usr/bin/env python
import getopt
import socket
import sys
import time
import csv
import socket
import bluepy

import sensortag
from sensortag import SensorTag

from coapthon.client.helperclient import HelperClient
from coapthon.utils import parse_uri

import getopt
import sys
from coapthon.server.coap import CoAP
from exampleresources import BasicResource, SensortagResource
client = None

class BTException(Exception):
    def __init__(self, code):
        self.code=code
    def __str__(self):
        return repr(self.code)

class CoAPServer(CoAP):
    def __init__(self, host, port, multicast=False):
        CoAP.__init__(self, (host, port), multicast)

        print "CoAP Server start on " + host + ":" + str(port)
        #print "Awaiting probe..."


def client_callback(response):
    print "Callback"


def client_callback_observe(response):  # pragma: no cover
    global client
    print "Callback_observe"
    check = True
    while check:
        chosen = raw_input("Stop observing? [y/N]: ")
        if chosen != "" and not (chosen == "n" or chosen == "N" or chosen == "y" or chosen == "Y"):
            print "Unrecognized choose."
            continue
        elif chosen == "y" or chosen == "Y":
            while True:
                rst = raw_input("Send RST message? [Y/n]: ")
                if rst != "" and not (rst == "n" or rst == "N" or rst == "y" or rst == "Y"):
                    print "Unrecognized choose."
                    continue
                elif rst == "" or rst == "y" or rst == "Y":
                    client.cancel_observing(response, True)
                else:
                    client.cancel_observing(response, False)
                check = False
                break
        else:
            break


def main():  # pragma: no cover

    ip = "0.0.0.0"
    port = 5683
    multicast = False
    
    server = CoAPServer(ip, port, multicast)

    thing = []

    with open('CoAPserverconf.txt') as csvfile:
        inputreader = csv.reader(csvfile)
        for row in inputreader:
            thing.append(row)

    for obj in thing:
        if obj[0] == 'c':
            resourceName = obj[1]
            foreignIP = obj[2]
            foreignPort = obj[3]
            foreignURI = obj[4]
            server.add_resource(resourceName, BasicResource("1",None,rt="interact", fIP=foreignIP, fPort=int(foreignPort), fPath=foreignURI))
            print("CoAP device with URI "+resourceName+" added!")
        elif obj[0] == 'b':
            if(obj[1] == '2650'):
                tagHost=obj[2]
                try:
                    tag = SensorTag(tagHost)
                    tag.IRtemperature.enable()
                    tag.humidity.enable()
                    tag.barometer.enable()
                    tag.accelerometer.enable()
                    tag.magnetometer.enable()
                    tag.gyroscope.enable()
                    tag.battery.enable()
                    tag.lightmeter.enable()
                    server.add_resource('/temperature', SensortagResource(tag.IRtemperature))
                    server.add_resource('/humidity', SensortagResource(tag.humidity))
                    server.add_resource('/barometer', SensortagResource(tag.barometer))
                    server.add_resource('/accelerometer', SensortagResource(tag.accelerometer))
                    server.add_resource('/magnetometer', SensortagResource(tag.magnetometer))
                    server.add_resource('/gyroscope', SensortagResource(tag.gyroscope))
                    server.add_resource('/battery', SensortagResource(tag.battery))
                    server.add_resource('/lightmeter', SensortagResource(tag.lightmeter))
                    print("BLUETOOTH device type CC2650 added")
                except Exception as e:
                    print "BLUETOOTH device not connected. Error code: ",e.code
                
            else:
                print("IMPROPER BLUETOOTH INFORMATION IN INPUT\n")
        else:
            print("UNKNOWN RESOURCE TYPE DECLARATION IN INPUT\n")
    
    server.root.dump;
    print "awaiting PROBE..."
    try:
        server.listen(10)
    except KeyboardInterrupt:
        print "Server Shutdown"
        server.close()
        print "Exiting..."

    #tag.waitForNotifications(arg.t)


main()


