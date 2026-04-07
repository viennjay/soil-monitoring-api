from flask import Flask, request, jsonify
from datetime import datetime, date
import time
import logging
from flask_cors import CORS

app = Flask(__name__)
CORS(app)

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Latest sensor data (in-memory storage)
latest_data = {
    "moisture": 0,
    "moistureRaw": 0,
    "temperature": 0,
    "humidity": 0,
    "heatIndex": 0,
    "led1": 0,
    "led2": 0,
    "led3": 0,
    "timestamp": time.time(),
    "moistureStatus": "Unknown",
    "currentDate": datetime.now().strftime("%Y-%m-%d"),
    "currentTime": datetime.now().strftime("%H:%M:%S")
}

@app.route('/api/data', methods=['GET'])
def get_data():
    """API endpoint to get the latest sensor data"""
    return jsonify(latest_data)

@app.route('/update_data', methods=['POST'])
def update_data():
    global latest_data
    try:
        new_sensor_reading = request.get_json()
        
        latest_data['moisture'] = new_sensor_reading.get('moisture', 0)
        latest_data['moistureRaw'] = new_sensor_reading.get('moistureRaw', 0)
        latest_data['temperature'] = new_sensor_reading.get('temperature', 0)
        latest_data['humidity'] = new_sensor_reading.get('humidity', 0)
        latest_data['heatIndex'] = new_sensor_reading.get('heatIndex', 0)
        latest_data['moistureStatus'] = "Live Data Connected"
        latest_data['timestamp'] = time.time()
        latest_data['currentTime'] = datetime.now().strftime("%H:%M:%S")
        
        return jsonify({"status": "success", "message": "Data received by Cloud API"})
    except Exception as e:
        return jsonify({"status": "error", "message": str(e)}), 400

@app.route('/', methods=['GET'])
def home():
    return "Soil Monitoring API is live!"

if __name__ == "__main__":
    logger.info("Starting Flask server on port 5000")
    app.run(host='0.0.0.0', port=5000, debug=True)