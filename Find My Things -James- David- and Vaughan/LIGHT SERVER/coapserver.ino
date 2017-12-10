/*
ESP-COAP Server
*/

#include <ESP8266WiFi.h>
#include <coap_server.h>


// CoAP server endpoint url callback
void callback_light(coapPacket &packet, IPAddress ip, int port, int obs);
void callback_notlight(coapPacket &packet, IPAddress ip, int port, int obs);

coapServer coap;

//WiFi connection info
const char* ssid = "HakunaMatata";
const char* password = "";

// LED STATE
bool LEDSTATE;

// CoAP server endpoint URL
void callback_light(coapPacket *packet, IPAddress ip, int port,int obs) {
  Serial.println("light");

  // send response
  char p[packet->payloadlen + 1];
  memcpy(p, packet->payload, packet->payloadlen);
  p[packet->payloadlen] = NULL;
  Serial.println(p);

  String message(p);

  if (message.equals("0"))
  {
    digitalWrite(16,LOW);
    Serial.println("if loop");
  }
  else if (message.equals("1"))
  {
    digitalWrite(16,HIGH);
    Serial.println("else loop");
  } 
  char *light = (digitalRead(16) > 0)? ((char *) "1") :((char *) "0");
  
   //coap.sendResponse(packet, ip, port, light);
   if(obs==1)
    coap.sendResponse(light);
   else
    coap.sendResponse(ip,port,light);
 
}

void callback_notlight(coapPacket *packet, IPAddress ip, int port,int obs) {
  Serial.println("notlight");

  // send response
  char p[packet->payloadlen + 1];
  memcpy(p, packet->payload, packet->payloadlen);
  p[packet->payloadlen] = NULL;
  Serial.println(p);

  String message(p);

  char *notlight = "notlight here";
  
   //coap.sendResponse(packet, ip, port, light);
   if(obs==1)
    coap.sendResponse(notlight);
   else
    coap.sendResponse(ip,port,notlight);
 
}

void setup() {
  yield();
  //serial begin
  Serial.begin(115200);

  WiFi.begin(ssid);
  Serial.println(" ");

  // Connect to WiFi network
  Serial.println();
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    //delay(500);
    yield();
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");
  // Print the IP address
  Serial.println(WiFi.localIP());
  Serial.print("MAC: ");
  Serial.println(WiFi.macAddress());

  // LED State
  pinMode(16, OUTPUT);
  digitalWrite(16, HIGH);
  LEDSTATE = true;

  //pinMode(5, OUTPUT);
  //digitalWrite(5, HIGH);
  //LEDSTATE = true;


  // add server url endpoints.
  // can add multiple endpoint urls.

  coap.server(callback_light, "light", "interact");
  coap.server(callback_notlight,"notlight", "observe");

  // start coap server/client
  coap.start();
  // coap.start(5683);
}

void loop() {
  coap.loop();
  delay(1000);


}
