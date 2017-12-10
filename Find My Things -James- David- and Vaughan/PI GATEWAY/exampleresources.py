import time
from coapthon import defines
from coapthon.client.helperclient import HelperClient
from coapthon.resources.resource import Resource
import sensortag

__author__ = 'Giacomo Tanganelli'

class BasicResource(Resource):
    def __init__(self, name="BasicResource", coap_server=None, rt="observe", fIP="", fPort=None, fPath=""):
        super(BasicResource, self).__init__(name, coap_server, visible=True,
                                            observable=True, allow_children=True)
        self.payload = name
        self.resource_type = rt
        self.content_type = "text/plain"
        self.interface_type = "if1"
        self.foreignIP = fIP
        self.foreignPORT = fPort
        self.foreignPATH = fPath

    def render_GET(self, request):
        client = HelperClient(server=(self.foreignIP, self.foreignPORT))
        response = client.get(self.foreignPATH)

        print response.pretty_print()

        self.payload = response.payload
        
        return self

    def render_PUT(self, request):
        requestPayload = request.payload
        client = HelperClient(server=(self.foreignIP, self.foreignPORT))
        response = client.put(self.foreignPATH, requestPayload)

        print response.pretty_print()

        if(response.payload == "PUT OK"):
            self.edit_resource(request)

        client.stop()
        return self

    def render_POST(self, request):
        res = self.init_resource(request, BasicResource())
        return res

    def render_DELETE(self, request):
        return True

class SensortagResource(Resource):
    def __init__(self, sTag, name="SensortagResource", coap_server=None, rt="observe"):
        super(SensortagResource, self).__init__(name, coap_server, visible=True,
                                            observable=True, allow_children=True)
        self.payload = name
        self.resource_type = rt
        self.content_type = "text/plain"
        self.interface_type = "if1"
        self.tag = sTag

    def render_GET(self, request):
        response = str(self.tag.read())
        bad = "()"
        for c in bad:
            response = response.replace(c, "")
        floats = response.split(",")
        labels = self.tag.labels().split(",")
        response = []
        for f in floats:
            response.append(labels.pop(0) + ', ' + f)
        bad = "()'[]"
        for c in bad:
            response = str(response).replace(c, "")
        self.payload = response
        
        return self

    def render_PUT(self, request):
        return self

    def render_POST(self, request):
        res = self.init_resource(request, BasicResource())
        return res

    def render_DELETE(self, request):
        return True



