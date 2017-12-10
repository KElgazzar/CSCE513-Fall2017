using EXILANT.Labs.CoAP.Channels;
using EXILANT.Labs.CoAP.Helpers;
using EXILANT.Labs.CoAP.Message;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Timers;

namespace Find_My_Things.CoAPManager
{
    class COAPManager
    {
        private bool timeOut = false;
        public string serverIP { get; set; }
        private CoAPClientChannel _coapClient = null;
        private string _mToken = "";
        int serverPort = 5683;
        public string _selectedGroup { get; set; }
        public string tempAsJSON = "";
        public List<Service> Services = new List<Service>();
        public List<Service> ObserveServices = new List<Service>();
        public List<Service> InteractServices = new List<Service>();
        private int delay = 5000;

        private ushort id = 0;

        public COAPManager(string ip)
        {
            serverIP = ip;
            InitializeClient();
        }

        private void InitializeClient()
        {
            _coapClient = new CoAPClientChannel();
            _coapClient.Initialize(serverIP, serverPort);
            _coapClient.CoAPResponseReceived += new CoAPResponseReceivedHandler(OnCoAPResponseReceived);
            _coapClient.CoAPRequestReceived += new CoAPRequestReceivedHandler(OnCoAPRequestReceived);
            _coapClient.CoAPError += new CoAPErrorHandler(OnCoAPError);
        }

        public bool GetRequest()
        {
            CoAPRequest coapReq = new CoAPRequest(CoAPMessageType.CON,
                                                  CoAPMessageCode.GET,
                                                  id);
            id++;

            string uriToCall = "coap://" + serverIP + ":" + serverPort + "/.well-known/core";
            coapReq.SetURL(uriToCall);
            _mToken = DateTime.Now.ToString("HHmmss");//Token value must be less than 8 bytes
            coapReq.Token = new CoAPToken(_mToken);//A random token

            _coapClient.Send(coapReq);

            tempAsJSON = "";
            timeOut = false;

            Timer timer = new Timer(delay);
            timer.Elapsed += Timer_Elapsed;

            timer.Start();

            while (tempAsJSON == string.Empty && timeOut == false) ;

            timer.Stop();

            if (timeOut == true && tempAsJSON == string.Empty)
                return false;

            Services.Clear();
            ObserveServices.Clear();
            InteractServices.Clear();

            ParseServices(tempAsJSON);

            string s = tempAsJSON;

            tempAsJSON = "";

            return true;            
        }

        public string GetDeviceStatus(string location)
        {
            CoAPRequest coapReq = new CoAPRequest(CoAPMessageType.CON,
                                                 CoAPMessageCode.GET,
                                                 id);
            id++;

            string uriToCall = $"coap://{serverIP}:{serverPort}{location}";
            coapReq.SetURL(uriToCall);
            _mToken = DateTime.Now.ToString("HHmmss");//Token value must be less than 8 bytes
            coapReq.Token = new CoAPToken(_mToken);//A random token

            _coapClient.Send(coapReq);

            tempAsJSON = "";
            timeOut = false;

            Timer timer = new Timer(delay);
            timer.Elapsed += Timer_Elapsed;

            timer.Start();

            while (tempAsJSON == string.Empty && timeOut == false) ;

            timer.Stop();

            if (timeOut == true && tempAsJSON == string.Empty)
                return "not found";                      

            string s = tempAsJSON;

            tempAsJSON = "";

            return s;
        }

        private void Timer_Elapsed(object sender, ElapsedEventArgs e)
        {
            timeOut = true;
        }

        private void ParseServices(string message)
        {
            string[] serviceStrings = message.Split(',');
    
            foreach (string item in serviceStrings)
            {
                if (item == string.Empty)
                {
                    continue;
                }

                Service newService = new Service();
                string[] serviceProperties = item.Split(';');

                //string location = serviceProperties[0].Substring(1,serviceProperties[0].Length-2);
                //string name = serviceProperties[1];
                //string resourceType = serviceProperties[2].Substring(4,serviceProperties[2].Length-5).Trim(' ');

                string location = serviceProperties[0].Substring(1, serviceProperties[0].Length - 2);
                string name = serviceProperties[0].Substring(2, serviceProperties[0].Length - 3);
                string resourceType = serviceProperties[1].Substring(4, serviceProperties[1].Length - 5).Trim(' ');

                //string test = serviceProperties[2];                
                newService.Location = location;
                newService.Name = name;
                newService.ResourceType = resourceType;

                if (resourceType == "observe")
                {
                    ObserveServices.Add(newService);
                }
                else if (resourceType == "interact")
                {
                    InteractServices.Add(newService);
                }

                Services.Add(newService);
            }
        }

        public void SendMessage(string message, string location)
        {
            CoAPRequest coapReq = new CoAPRequest(CoAPMessageType.CON,
                                                   CoAPMessageCode.PUT,
                                                   id);//hardcoded message ID as we are using only once
            id++;

            string uriToCall = $"coap://{serverIP}:{serverPort}{location}";
            coapReq.SetURL(uriToCall);
            _mToken = DateTime.Now.ToString("HHmmss");//Token value must be less than 8 bytes
            coapReq.Token = new CoAPToken(_mToken);//A random token

            coapReq.Payload = new CoAPPayload(message);
            
            _coapClient.Send(coapReq);            
        }

        private void OnCoAPError(Exception e, AbstractCoAPMessage associatedMsg)
        {
            throw new NotImplementedException();
        }

        private void OnCoAPRequestReceived(CoAPRequest coapReq)
        {
            throw new NotImplementedException();
        }

        private void OnCoAPResponseReceived(CoAPResponse coapResp)
        {
            string tokenRx = (coapResp.Token != null && coapResp.Token.Value != null) ? AbstractByteUtils.ByteToStringUTF8(coapResp.Token.Value) : "";
            if (tokenRx == _mToken)
            {             
                if (coapResp.Code.Value == CoAPMessageCode.CONTENT)
                {                
                    tempAsJSON = AbstractByteUtils.ByteToStringUTF8(coapResp.Payload.Value);                          
                }
                else
                {                    
                }
            }

        }
    }
}