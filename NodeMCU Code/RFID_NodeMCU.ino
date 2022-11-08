/*
  NodeMCU RFID System
  Marc Antony Martínez Mejía
  Marlene Medellin González

  Notas: 
  Usar la libreria de NodeMCU en la version 2.4.2 para evitar errores de compilación.
*/

#include <ESP8266WiFi.h>     //Include Esp library
#include <WiFiClient.h> 
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>        //include RFID library
#include<Wire.h>
#include<LiquidCrystal_I2C.h>
LiquidCrystal_I2C lcd(0x27, 16, 2);

#define SS_PIN D8 //RX slave select
#define RST_PIN D3
#define RedLed D0
#define BlueLed D4

MFRC522 mfrc522(SS_PIN, RST_PIN); // Create MFRC522 instance.

/* Set these to your desired credentials. */
const char *ssid = "TP-LINK_2892";  //ENTER YOUR WIFI SETTINGS
const char *password = "21608018";

//Web/Server address to read/write from 
const char *host = "192.168.1.117";   //IP address of server

String getData ,Link;
String CardID="";

void setup() {
  delay(1000);
  Serial.begin(115200);
  SPI.begin();  // Init SPI bus
  mfrc522.PCD_Init(); // Init MFRC522 card

  WiFi.mode(WIFI_OFF);        //Prevents reconnection issue (taking too long to connect)
  delay(1000);
  WiFi.mode(WIFI_STA);        //This line hides the viewing of ESP as wifi hotspot
  
  //initialize lcd screen
  lcd.init();
  // turn on the backlight
  lcd.backlight();
  lcd.clear();
  
  WiFi.begin(ssid, password);     //Connect to your WiFi router
  Serial.println("");

  Serial.print("Connecting to ");
  Serial.print(ssid);
  
  //message lcd connected to WiFi
  lcd.clear();
  lcd.setCursor(0,0); //col=0 row=0
  lcd.print("Conectando a");
  lcd.setCursor(0,1); //col=0 row=0
  lcd.print(ssid);
  delay(500);

  // Wait for connection
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  //If connection successful show IP address in serial monitor
  Serial.println("");
  Serial.println("Connected");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());  //IP address assigned to your ESP

  //message lcd IP connected
  lcd.clear();
  lcd.setCursor(0,0); //col=0 row=0
  lcd.print("Conectado a WiFi");
  lcd.setCursor(0,1); //col=0 row=0
  lcd.print("IP:");
  lcd.print(WiFi.localIP());
  delay(5000);

  pinMode(RedLed,OUTPUT);
  pinMode(BlueLed,OUTPUT); 
}

void loop() {
  if(WiFi.status() != WL_CONNECTED){
    WiFi.disconnect();
    WiFi.mode(WIFI_STA);
    Serial.print("Reconnecting to ");
    Serial.println(ssid);
    WiFi.begin(ssid, password);
    //message lcd reconexion
    lcd.clear();
    lcd.setCursor(0,0); //col=0 row=0
    lcd.print("Reconectando a");
    lcd.setCursor(0,1); //col=0 row=0
    lcd.print(ssid);
    delay(5000);


  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
    Serial.println("");
    Serial.println("Connected");
    Serial.print("IP address: ");
    Serial.println(WiFi.localIP());  //IP address assigned to your ESP

    //message lcd reconectado
    lcd.clear();
    lcd.setCursor(0,0); //col=0 row=0
    lcd.print("Conectado a WiFi");
    lcd.setCursor(0,1); //col=0 row=0
    lcd.print("IP:");
    lcd.print(WiFi.localIP());
    delay(5000); 
  }
  
  //look for new card
   if ( ! mfrc522.PICC_IsNewCardPresent()) {
  return;//got to start of loop if there is no card present
 }
 // Select one of the cards
 if ( ! mfrc522.PICC_ReadCardSerial()) {
  return;//if read card serial(0) returns 1, the uid struct contians the ID of the read card.
 }

 for (byte i = 0; i < mfrc522.uid.size; i++) {
     CardID += mfrc522.uid.uidByte[i];
}
  
  HTTPClient http;    //Declare object of class HTTPClient
  
  //GET Data
  getData = "?CardID=" + CardID;  //Note "?" added at front
  Link = "http://192.168.1.117/loginsystem/postdemo.php" + getData;
  
  http.begin(Link);
  
  int httpCode = http.GET();            //Send the request
  delay(10);
  String payload = http.getString();    //Get the response payload
  
  Serial.println(httpCode);   //Print HTTP return code
  Serial.println(payload);    //Print request response payload
  Serial.println(CardID);     //Print Card ID
  
  //message lcd CARD
  lcd.clear();
  lcd.setCursor(0,0); //col=0 row=0
  lcd.print(payload);
  lcd.setCursor(0,1); //col=0 row=0
  lcd.print("ID: ");
  lcd.print(CardID);
  delay(500);
  
  if(payload == "login"){
    digitalWrite(RedLed,HIGH);
    Serial.println("red on");
    delay(500);  //Post Data at every 5 seconds
  }
  else if(payload == "logout"){
    digitalWrite(BlueLed,HIGH);
    Serial.println("Blue on");
    delay(500);  //Post Data at every 5 seconds
  }
  else if(payload == "succesful" || payload == "Cardavailable"){
    digitalWrite(BlueLed,HIGH);
    digitalWrite(RedLed,HIGH);
    delay(500);  
  }
  delay(500);
  
  CardID = "";
  getData = "";
  Link = "";
  http.end();  //Close connection
  
  digitalWrite(RedLed,LOW);
  digitalWrite(BlueLed,LOW);
}
//=======================================================================
