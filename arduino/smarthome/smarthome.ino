/*
  Adaam Smart Home ESP8266 Arduino Sketch

  Functionality:
  - Connects to WiFi
  - Periodically fetches device states from backend API (GET /api/devices)
  - Reflects each device's state on assigned GPIO pins
  - Can send POST /api/toggle to toggle device state on backend

  Devices managed:
  - lamp, fan, door, window, pc, tv

  Configuration:
  - Set your WiFi SSID and PASSWORD below
  - Set your backend server IP or hostname (including port if not 80)
*/

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>

#define WIFI_SSID "F11"
#define WIFI_PASSWORD "11221122"

// Backend server base URL (replace with your backend IP/domain)
const char *backendHost = "10.154.207.110:8000"; // e.g. IP of host running Laravel backend

// Device GPIO pin assignments - adjust according to your ESP8266 board wiring
const int PIN_LAMP = D1;    // D1
const int PIN_FAN = D2;     // D2
const int PIN_DOOR = D5;   // D5
const int PIN_WINDOW = D6; // D6
const int PIN_PC = D7;     // D7
const int PIN_TV = D8;     // D8

// Time between device states fetch (1200 ms similar to frontend)
const unsigned long FETCH_INTERVAL_MS = 1200;

unsigned long lastFetchTime = 0;

// Device names list
const char *deviceNames[] = {"lamp", "fan", "door", "window", "pc", "tv"};
const int devicePins[] = {PIN_LAMP, PIN_FAN, PIN_DOOR, PIN_WINDOW, PIN_PC, PIN_TV};
const int numDevices = sizeof(deviceNames) / sizeof(deviceNames[0]);

WiFiClient client;
HTTPClient http;

void setup()
{
    Serial.begin(115200);
    delay(10);

    // Setup output pins
    for (int i = 0; i < numDevices; i++)
    {
        pinMode(devicePins[i], OUTPUT);
        digitalWrite(devicePins[i], LOW); // Start off
    }

    connectToWiFi();
}

void loop()
{
    if (WiFi.status() != WL_CONNECTED)
    {
        connectToWiFi();
    }

    unsigned long currentMillis = millis();
    if (currentMillis - lastFetchTime >= FETCH_INTERVAL_MS)
    {
        fetchDeviceStates();
        lastFetchTime = currentMillis;
    }

    // Example input handling could be added here (e.g. serial commands or button triggers)
    // For example, toggle a device by sending POST /api/toggle using toggleDevice("lamp");

    delay(10);
}

void connectToWiFi()
{
    Serial.print("Connecting to WiFi...");
    WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
    while (WiFi.status() != WL_CONNECTED)
    {
        delay(500);
        Serial.print(".");
    }
    Serial.println(" connected!");
    Serial.print("IP address: ");
    Serial.println(WiFi.localIP());
}

void fetchDeviceStates()
{
    if (http.begin(client, String(backendHost) + "/api/devices"))
    {
        int httpCode = http.GET();
        if (httpCode == HTTP_CODE_OK)
        {
            String payload = http.getString();
            StaticJsonDocument<256> doc;
            DeserializationError error = deserializeJson(doc, payload);
            if (!error)
            {
                // Parse state for each device and set GPIO output
                for (int i = 0; i < numDevices; i++)
                {
                    bool state = doc[deviceNames[i]] | false;
                    digitalWrite(devicePins[i], state ? HIGH : LOW);
                    Serial.print(deviceNames[i]);
                    Serial.print(": ");
                    Serial.println(state ? "ON" : "OFF");
                }
            }
            else
            {
                Serial.print("JSON parse error: ");
                Serial.println(error.c_str());
            }
        }
        else
        {
            Serial.print("GET /api/devices failed, code: ");
            Serial.println(httpCode);
        }
        http.end();
    }
    else
    {
        Serial.println("Unable to connect to backend for GET /api/devices");
    }
}

// Toggle a device by sending POST to backend API
bool toggleDevice(const char *deviceName, bool state)
{
    if (http.begin(client, String(backendHost) + "/api/toggle"))
    {
        http.addHeader("Content-Type", "application/json");
        StaticJsonDocument<64> doc;
        doc["name"] = deviceName;
        doc["state"] = state;
        String requestBody;
        serializeJson(doc, requestBody);

        int httpCode = http.POST(requestBody);
        if (httpCode == HTTP_CODE_OK)
        {
            Serial.print("Toggled device: ");
            Serial.println(deviceName);
            http.end();
            return true;
        }
        else
        {
            Serial.print("POST /api/toggle failed, code: ");
            Serial.println(httpCode);
        }
        http.end();
    }
    else
    {
        Serial.println("Unable to connect to backend for POST /api/toggle");
    }
    return false;
}
