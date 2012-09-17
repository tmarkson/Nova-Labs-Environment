//ARDUINO 1.0+ ONLY
#include <Ethernet.h>
#include <SPI.h>
#include <Timer.h>

//CONFIGURE
byte server[] = { 206,220,173,113 }; //ip Address of the server you will connect to

boolean doorSwitchValue = 0;
int doorSwitchPin = 5;

byte mac[] = {	0x90, 0xA2, 0xDA, 0x0D, 0x1C, 0x06 };	// novalabs shield

EthernetClient client;
Timer t;

void setup()
{
	Ethernet.begin(mac);
	Serial.begin(9600);

	// Print local IP address
	Serial.println(Ethernet.localIP());

	// Set door switch pin to an input
	pinMode(doorSwitchPin, INPUT);

	// Start a timer to check door switch state
	int timerID = t.every(500, checkSwitch);
	Serial.print("Checking door switch state twice every second. Timer id = ");
	Serial.println(timerID);
}

void loop()
{
	// TBI - if there's no ethernet, stop the timer

	t.update();	

	if (client.available())
	{
		char c = client.read();
		Serial.print(c);
	}
}

void checkSwitch()
{
	// get current door switch value
	boolean newDoorSwitchValue = digitalRead(doorSwitchPin);

	// if door switch value changed since last check
	if (newDoorSwitchValue != doorSwitchValue)
	{
		Serial.print("Switch changed! Value is ");
		Serial.println(newDoorSwitchValue);
		// store new door switch value
		doorSwitchValue = newDoorSwitchValue;
		connectAndRead();
	}
}
void connectAndRead()
{
	// Connect to the server
	Serial.println("Connecting...");

	// Send status if connect success
	if (client.connect(server, 80))
	{
		Serial.println("Connected.");
		Serial.println("Sending status to server...");

		String sendStr = "GET /~lumisens/nova-labs/status/SetStatus.php?author=arduino&switch="+doorSwitchValue+" HTTP/1.1";
		client.println(sendStr);
		client.println("Host: www.lumisense.com");
		client.println("User-Agent: arduino-ethernet");
		client.println("Connection: close");
		client.println();

		client.stop();
		Serial.println("Disconnected.");
	}

	else
	{
		Serial.println("Connection Failed.");
	}

}