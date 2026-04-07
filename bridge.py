import serial
import requests
import json
import time

CLOUD_API_URL = "https://soil-monitoring-api.onrender.com/update_data"

SERIAL_PORT = 'COM3' 

try:
    arduino = serial.Serial(SERIAL_PORT, 9600, timeout=1)
    print(f"Connected to Arduino on {SERIAL_PORT}")
except Exception as e:
    print(f"Error: Could not find Arduino on {SERIAL_PORT}. Please check the port.")
    exit()

print("Monitoring live data... Press Ctrl+C to stop.")

while True:
    if arduino.in_waiting > 0:
        try:
            # Read data from the USB cable
            line = arduino.readline().decode('utf-8').strip()
            
            if line:
                # Convert the Arduino string into a Python dictionary
                data = json.loads(line)
                
                # Push the data to the Python API
                response = requests.post(CLOUD_API_URL, json=data)
                print(f"Sent to Cloud: {data} -> Status: {response.status_code}")
                
        except json.JSONDecodeError:
            print("Received invalid JSON from Arduino. Skipping...")
        except requests.exceptions.RequestException as e:
            print(f"Failed to connect to the Cloud API: {e}")
            
    time.sleep(2)