#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>

// WiFi Credentials
const char* ssid = "realme 8";        // Change this to your WiFi name
const char* password = "11111111";   // Change this to your WiFi password

// Create web server on port 80
ESP8266WebServer server(80);

// Pin Definitions
int LED1 = D1;  // GPIO5
int LED2 = D2;  // GPIO4
int LED3 = D3;  // GPIO0

// Variables for sensor data
int currentMoistureRaw = 0;
int currentMoisturePercent = 0;
bool led1State = false;
bool led2State = false;
bool led3State = false;

// CORS headers function
void setCORSHeaders() {
  server.sendHeader("Access-Control-Allow-Origin", "*");
  server.sendHeader("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
  server.sendHeader("Access-Control-Allow-Headers", "Content-Type");
}

// Handle OPTIONS request for CORS preflight
void handleOptions() {
  setCORSHeaders();
  server.send(204);
}

// Handle root page
void handleRoot() {
  setCORSHeaders();
  String html = "<html><body>";
  html += "<h1>ESP8266 Soil Moisture Monitor</h1>";
  html += "<p>Device is running!</p>";
  html += "<p>IP Address: " + WiFi.localIP().toString() + "</p>";
  html += "<p>Signal Strength: " + String(WiFi.RSSI()) + " dBm</p>";
  html += "<p>Current Moisture: " + String(currentMoisturePercent) + "%</p>";
  html += "<p>Raw Value: " + String(currentMoistureRaw) + "</p>";
  html += "<p><a href='/data'>View JSON Data</a></p>";
  html += "</body></html>";
  server.send(200, "text/html", html);
}

// Handle data request (JSON endpoint for dashboard)
void handleData() {
  setCORSHeaders();
  
  // Create JSON response
  String json = "{";
  json += "\"moisture\":" + String(currentMoisturePercent) + ",";
  json += "\"moistureRaw\":" + String(currentMoistureRaw) + ",";
  json += "\"led1\":" + String(led1State ? "true" : "false") + ",";
  json += "\"led2\":" + String(led2State ? "true" : "false") + ",";
  json += "\"led3\":" + String(led3State ? "true" : "false") + ",";
  json += "\"wifiConnected\":true,";
  json += "\"ipAddress\"😕"" + WiFi.localIP().toString() + "\",";
  json += "\"rssi\":" + String(WiFi.RSSI());
  json += "}";
  
  server.send(200, "application/json", json);
  
  // Print to serial for debugging
  Serial.println("Data request served: " + json);
}

// Read and update sensor data (using first file's thresholds)
void updateSensorData() {
  // Read soil moisture from analog pin A0
  currentMoistureRaw = analogRead(A0);
  
  // Map the sensor value to a percentage
  currentMoisturePercent = map(currentMoistureRaw, 1023, 0, 0, 100);
  
  // Control LEDs based on moisture levels (FIRST FILE THRESHOLDS)
  if (currentMoistureRaw >= 1000) {
    // Dry soil
    digitalWrite(LED1, HIGH);
    digitalWrite(LED2, LOW);
    digitalWrite(LED3, LOW);
    led1State = true;
    led2State = false;
    led3State = false;
  }
  else if (currentMoistureRaw >= 675 && currentMoistureRaw < 950) {
    // Medium moisture
    digitalWrite(LED2, HIGH);
    digitalWrite(LED1, LOW);
    digitalWrite(LED3, LOW);
    led1State = false;
    led2State = true;
    led3State = false;
  }
  else if (currentMoistureRaw < 675) {
    // Wet soil
    digitalWrite(LED3, HIGH);
    digitalWrite(LED1, LOW);
    digitalWrite(LED2, LOW);
    led1State = false;
    led2State = false;
    led3State = true;
  }
}

void setup() {
  Serial.begin(9600);
  delay(1000);
  
  Serial.println("\n\n=== ESP8266 Starting ===");
  Serial.println("Serial communication working!");
  
  // Declare pins to be output
  pinMode(LED1, OUTPUT);
  pinMode(LED2, OUTPUT);
  pinMode(LED3, OUTPUT);
  
  // Initialize LEDs
  digitalWrite(LED1, HIGH);
  digitalWrite(LED2, LOW);
  digitalWrite(LED3, LOW);
  
  Serial.println("\n\nSoil Moisture Monitoring System");
  Serial.println("================================");
  Serial.println("LEDs initialized");
  
  // Connect to WiFi
  Serial.print("Connecting to WiFi: ");
  Serial.println(ssid);
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  
  int attempts = 0;
  while (WiFi.status() != WL_CONNECTED && attempts < 30) {
    delay(500);
    Serial.print(".");
    attempts++;
  }
  
  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\n\nWiFi Connected Successfully!");
    Serial.println("================================");
    Serial.print("IP Address: ");
    Serial.println(WiFi.localIP());
    Serial.print("Signal Strength (RSSI): ");
    Serial.print(WiFi.RSSI());
    Serial.println(" dBm");
    Serial.println("================================");
    Serial.println("\nWeb Server Started!");
    Serial.println("Access points:");
    Serial.print("  - Root: http://");
    Serial.println(WiFi.localIP());
    Serial.print("  - Data: http://");
    Serial.print(WiFi.localIP());
    Serial.println("/data");
    Serial.println("\nOpen your browser and enter this IP in the dashboard!");
    Serial.println("================================\n");
  } else {
    Serial.println("\n\nWiFi Connection Failed!");
    Serial.println("Please check your credentials and try again.");
    Serial.println("Continuing without WiFi...");
  }
  
  // Setup web server routes
  server.on("/", HTTP_GET, handleRoot);
  server.on("/data", HTTP_GET, handleData);
  server.on("/data", HTTP_OPTIONS, handleOptions);
  server.onNotFound([]() {
    setCORSHeaders();
    server.send(404, "text/plain", "Not Found");
  });
  
  // Start web server
  server.begin();
  Serial.println("HTTP server started");
  Serial.println("Starting sensor readings...\n");
  
  delay(2000);
}

void loop() {
  // Handle web server requests
  server.handleClient();

  // Print IP address every 30 seconds
  static unsigned long lastIPPrint = 0;
  if (millis() - lastIPPrint >= 30000) {
    lastIPPrint = millis();
    Serial.println("\n--- Device Info ---");
    Serial.print("IP Address: ");
    Serial.println(WiFi.localIP());
    Serial.print("RSSI: ");
    Serial.print(WiFi.RSSI());
    Serial.println(" dBm");
    Serial.println("------------------\n");
  }
  
  // Check WiFi connection and reconnect if needed
  if (WiFi.status() != WL_CONNECTED) {
    static unsigned long lastReconnect = 0;
    if (millis() - lastReconnect > 30000) {
      Serial.println("\nWiFi Disconnected! Reconnecting...");
      WiFi.begin(ssid, password);
      int attempts = 0;
      while (WiFi.status() != WL_CONNECTED && attempts < 20) {
        delay(500);
        Serial.print(".");
        attempts++;
      }
      if (WiFi.status() == WL_CONNECTED) {
        Serial.println("\nReconnected!");
        Serial.print("IP Address: ");
        Serial.println(WiFi.localIP());
      }
      lastReconnect = millis();
    }
  }
  
  // Update sensor readings every 1 second
  static unsigned long lastUpdate = 0;
  if (millis() - lastUpdate >= 1000) {
    lastUpdate = millis();
    updateSensorData();
    
    // Print current status to serial (for debugging and serial communication)
    Serial.print("Moisture: ");
    Serial.print(currentMoisturePercent);
    Serial.print("% | Raw: ");
    Serial.print(currentMoistureRaw);
    Serial.print(" | LEDs: ");
    Serial.print(led1State ? "DRY " : "");
    Serial.print(led2State ? "MEDIUM " : "");
    Serial.print(led3State ? "WET " : "");
    Serial.print("| RSSI: ");
    Serial.print(WiFi.RSSI());
    Serial.println(" dBm");
  }
  
  // Send JSON data every 5 seconds for serial monitoring
  static unsigned long lastSerialJSON = 0;
  if (millis() - lastSerialJSON >= 5000) {
    lastSerialJSON = millis();
    
    Serial.print("{\"moisture\":");
    Serial.print(currentMoisturePercent);
    Serial.print(",\"moistureRaw\":");
    Serial.print(currentMoistureRaw);
    Serial.print(",\"led1\":");
    Serial.print(led1State ? "true" : "false");
    Serial.print(",\"led2\":");
    Serial.print(led2State ? "true" : "false");
    Serial.print(",\"led3\":");
    Serial.print(led3State ? "true" : "false");
    Serial.print(",\"wifiConnected\":");
    Serial.print(WiFi.status() == WL_CONNECTED ? "true" : "false");
    Serial.print(",\"ipAddress\"😕"");
    Serial.print(WiFi.status() == WL_CONNECTED ? WiFi.localIP().toString() : "0.0.0.0");
    Serial.print("\",\"rssi\":");
    Serial.print(WiFi.status() == WL_CONNECTED ? WiFi.RSSI() : 0);
    Serial.println("}");
  }
  
  // Small delay to prevent watchdog reset
  delay(10);
}

