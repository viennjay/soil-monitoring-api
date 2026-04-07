
 #include <DHT.h>

 // Pin Definitions
 int LED1 = 3;
 int LED2 = 5;
 int LED3 = 6;

 
 // Variables for serial communication
 unsigned long previousMillis = 0;
 const long interval = 5000; 
 
 void setup() {
   Serial.begin(9600);
   
   // Declare pins 3, 5, 6 to be output
   pinMode(LED1, OUTPUT);
   pinMode(LED2, OUTPUT);
   pinMode(LED3, OUTPUT);
   
   // Initialize LEDs
   digitalWrite(LED1, HIGH);
   digitalWrite(LED2, LOW);
   digitalWrite(LED3, LOW);
   
   // Initialize DHT sensor
   dht.begin();
   
   Serial.println("Soil Moisture and Heat Index Monitoring System");
   delay(5000);
 }
 
 void loop() {
   // Read soil moisture from analog pin A0
   int sensorValue = analogRead(A0);
   
   // Map the sensor value to a percentage (adjust these values based on your sensor calibration)
   int moisturePercent = map(sensorValue, 1023, 0, 0, 100);
   
   // Read temperature and humidity from DHT sensor
   float humidity = dht.readHumidity();
   float temperature = dht.readTemperature();
   
   // Calculate heat index in Celsius
   float heatIndex = dht.computeHeatIndex(temperature, humidity, false);
   
   // Control LEDs based on moisture levels
   if (sensorValue >= 1000) {
     
     digitalWrite(LED1, HIGH);
     digitalWrite(LED2, LOW);
     digitalWrite(LED3, LOW);
   }
   else if (sensorValue >= 675 && sensorValue < 950) {
     
     digitalWrite(LED2, HIGH);
     digitalWrite(LED1, LOW);
     digitalWrite(LED3, LOW);
   }
   else if (sensorValue >= 0 && sensorValue < 675) {
     
     digitalWrite(LED3, HIGH);
     digitalWrite(LED1, LOW);
     digitalWrite(LED2, LOW);
   }
   
   
   Serial.print("{\"moisture\":");
   Serial.print(moisturePercent);
   Serial.print(",\"moistureRaw\":");
   Serial.print(sensorValue);
   Serial.print(",\"temperature\":");
   Serial.print(temperature);
   Serial.print(",\"humidity\":");
   Serial.print(humidity);
   Serial.print(",\"heatIndex\":");
   Serial.print(heatIndex);
   Serial.print(",\"led1\":");
   Serial.print(digitalRead(LED1));
   Serial.print(",\"led2\":");
   Serial.print(digitalRead(LED2));
   Serial.print(",\"led3\":");
   Serial.print(digitalRead(LED3));
   Serial.println("}");
   
   delay(5000);
 }