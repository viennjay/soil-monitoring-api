<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soil & Temperature Monitoring</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 20px;
            background-color: #f5f5f5;
        }
        .card {
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(6, 6, 6, 0.95);
        }
        .card-header {
            font-weight: bold;
        }
        .gauge {
            position: relative;
            height: 200px;
            width: 100px;
            margin: 0 auto;
            background-color: #e9ecef;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            overflow: hidden;
        }
        .gauge-value {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            z-index: 10;
            background: rgba(255, 255, 255, 0.9);
            padding: 5px 10px;
            border-radius: 5px;
        }
        .moisture-gauge-fill {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, #007bff, #66b3ff);
            transition: height 0.5s ease-in-out;
            border-radius: 0 0 8px 8px;
        }
        .led-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
            border: 2px solid #dee2e6;
            transition: all 0.3s ease;
            opacity: 0.4;
        }
        .led-on {
            opacity: 1;
            border-color: white;
            box-shadow: 0 0 15px 3px rgba(255, 255, 255, 0.8);
            transform: scale(1.1);
        }
        .led-1 {
            background-color: #dc3545;
        }
        .led-1.led-on {
            box-shadow: 0 0 15px 3px rgba(220, 53, 69, 0.8);
        }
        .led-2 {
            background-color: #ffc107;
        }
        .led-2.led-on {
            box-shadow: 0 0 15px 3px rgba(255, 193, 7, 0.9);
        }
        .led-3 {
            background-color: #28a745;
        }
        .led-3.led-on {
            box-shadow: 0 0 15px 3px rgba(40, 167, 69, 0.8);
        }
        .status-indicator {
            padding: 8px 15px;
            border-radius: 15px;
            font-weight: bold;
            color: white;
            display: inline-block;
            margin-top: 10px;
        }
        .status-dry { background-color: #dc3545; }
        .status-medium { background-color: #ffc107; color: black; }
        .status-wet { background-color: #28a745; }
        .status-unknown { background-color: #6c757d; }
        
        .connection-panel {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .wifi-controls {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
        .connection-status {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
        }
        .status-connected { background-color: #28a745; color: white; }
        .status-disconnected { background-color: #dc3545; color: white; }
        .status-connecting { background-color: #ffc107; color: black; }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        .pulse { animation: pulse 1s infinite; }
        
        .console {
            background-color: #000;
            color: #00ff00;
            padding: 15px;
            border-radius: 5px;
            font-family: monospace;
            height: 200px;
            overflow-y: auto;
            margin-top: 10px;
        }
        
        .threshold-info {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 10px;
            margin-top: 15px;
            border-radius: 0 5px 5px 0;
        }
        
        .cloud-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .encryption-indicator {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            padding: 10px;
            margin-top: 10px;
        }
        
        .cloud-status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: bold;
            display: inline-block;
            margin-left: 10px;
        }
        
        .cloud-success { background-color: #28a745; color: white; }
        .cloud-error { background-color: #dc3545; color: white; }
        .cloud-uploading { background-color: #ffc107; color: black; }
        
        @keyframes uploadPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .uploading { animation: uploadPulse 1s infinite; }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center mb-4"> Soil & Temperature Monitor</h1>
    
    
    <div class="cloud-section">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5>🔒 Encrypted Cloud Storage</h5>
                <p class="mb-2">Securely backup your monitoring data to JSONBin.io with AES-256 encryption</p>
                <div class="encryption-indicator">
                    <small>🔐 All data is encrypted with your password before upload</small>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="mb-2">
                    <input type="password" id="encryptionKey" class="form-control form-control-sm" 
                           placeholder="Encryption password" maxlength="50">
                </div>
                <button id="uploadToCloudBtn" class="btn btn-light btn-sm" disabled>
                    ☁️ Upload to Cloud
                </button>
                <div id="cloudStatus"></div>
            </div>
        </div>
    </div>
    
    <!-- Connection Panel -->
    <div class="connection-panel">
        <h5>ESP8266 NodeMCU WiFi Connection</h5>
        <div class="row">
            <div class="col-md-8">
                <div class="wifi-controls">
                    <input type="text" id="esp8266IpAddress" class="form-control" style="width: 200px;" 
                           placeholder="ESP8266 IP (e.g., 192.168.1.100)" value="10.135.41.106">
                    <button id="connectBtn" class="btn btn-primary">Connect to ESP8266</button>
                    <button id="disconnectBtn" class="btn btn-secondary" disabled>Disconnect</button>
                    <div class="connection-status status-disconnected" id="connectionStatus">
                        Disconnected
                    </div>
                </div>
                <div class="console" id="console">
                    <div>ESP8266 NodeMCU WiFi Console</div>
                    <div>Enter ESP8266 IP address and click "Connect to ESP8266"...</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-info">
                    <strong>Instructions:</strong>
                    <ol>
                        <li>Power on your ESP8266 NodeMCU</li>
                        <li>Connect to same WiFi network</li>
                        <li>Enter ESP8266 IP address</li>
                        <li>Click "Connect to ESP8266"</li>
                        <li>Data will appear automatically</li>
                    </ol>
                </div>
                <div class="threshold-info">
                    <strong>Moisture Thresholds:</strong>
                    <ul class="mb-0 mt-2">
                        <li><span class="text-danger fw-bold">Dry:</span> Raw ≥ 1000</li>
                        <li><span class="text-warning fw-bold">Medium:</span> Raw 675-949</li>
                        <li><span class="text-success fw-bold">Wet:</span> Raw < 675</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Soil Moisture
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div id="statusIndicator" class="status-indicator status-unknown">Unknown</div>
                    </div>
                    <div class="gauge">
                        <div class="moisture-gauge-fill" id="moistureFill" style="height: 1%;"></div>
                        <div class="gauge-value">
                            <span id="moistureValue">--</span>%
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <div>Raw Value: <span id="moistureRaw">--</span></div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center">
                                <span class="led-indicator led-1" id="led1"></span>
                                <span class="ms-1">Dry</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="led-indicator led-2" id="led2"></span>
                                <span class="ms-1">Medium</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="led-indicator led-3" id="led3"></span>
                                <span class="ms-1">Wet</span>
                            </div>
                        </div>
                        <small>Last updated: <span id="moistureTime">--</span></small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    Controls
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <h5>Data Collection</h5>
                        <p class="text-muted">Data is automatically saved every 5 seconds</p>
                    </div>
                    <div class="d-grid gap-2">
                        <button id="printDataBtn" class="btn btn-success" disabled>
                            🖨️ Print Current Data
                        </button>
                        <button id="exportDataBtn" class="btn btn-warning" disabled>
                            📥 Export as JSON
                        </button>
                    </div>
                    <div class="mt-3">
                        <small>Total Records: <span id="recordCount">0</span></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Data Table -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    Recent Data (Last 20 Records)
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Moisture (%)</th>
                                <th>Raw Value</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="dataTable">
                            <tr>
                                <td colspan="4" class="text-center text-muted">Connect to ESP8266 to see data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
<script>
// Global variables
let isConnected = false;
let dataHistory = [];
let currentData = null;
let dataInterval = null;
let fetchInterval = null;
let esp8266Ip = '';
const MAX_HISTORY = 20;
const DATA_SAVE_INTERVAL = 5000; // 5 seconds
const FETCH_INTERVAL = 2000; // 2 seconds

// Updated moisture thresholds (matching ESP8266 code)
const MOISTURE_THRESHOLDS = {
    DRY: 1000,     
    MEDIUM_LOW: 675, 
    MEDIUM_HIGH: 950,
    WET: 675       
};

// DOM elements
const connectBtn = document.getElementById('connectBtn');
const disconnectBtn = document.getElementById('disconnectBtn');
const connectionStatus = document.getElementById('connectionStatus');
const consoleDiv = document.getElementById('console');
const printDataBtn = document.getElementById('printDataBtn');
const exportDataBtn = document.getElementById('exportDataBtn');
const uploadToCloudBtn = document.getElementById('uploadToCloudBtn');
const encryptionKeyInput = document.getElementById('encryptionKey');
const cloudStatusDiv = document.getElementById('cloudStatus');
const esp8266IpInput = document.getElementById('esp8266IpAddress');

// Event listeners
connectBtn.addEventListener('click', connectToESP8266);
disconnectBtn.addEventListener('click', disconnectFromESP8266);
printDataBtn.addEventListener('click', printCurrentData);
exportDataBtn.addEventListener('click', exportData);
uploadToCloudBtn.addEventListener('click', uploadToCloud);

// Enable upload button when we have data and encryption key
function updateUploadButtonState() {
    const hasData = dataHistory.length > 0;
    const hasKey = encryptionKeyInput.value.trim().length >= 4;
    uploadToCloudBtn.disabled = !(hasData && hasKey);
}

encryptionKeyInput.addEventListener('input', updateUploadButtonState);

// Encryption functions
function encryptData(data, password) {
    try {
        const encrypted = CryptoJS.AES.encrypt(JSON.stringify(data), password).toString();
        return encrypted;
    } catch (error) {
        throw new Error('Encryption failed: ' + error.message);
    }
}

function generateSecureHash(data) {
    return CryptoJS.SHA256(JSON.stringify(data)).toString();
}

// Cloud storage functions
async function uploadToCloud() {
    const encryptionKey = encryptionKeyInput.value.trim();
    
    if (!encryptionKey || encryptionKey.length < 4) {
        showCloudStatus('Please enter at least 4 characters for encryption password', 'error');
        return;
    }
    
    if (dataHistory.length === 0) {
        showCloudStatus('No data to upload', 'error');
        return;
    }
    
    showCloudStatus('Encrypting and uploading...', 'uploading');
    uploadToCloudBtn.classList.add('uploading');
    uploadToCloudBtn.disabled = true;
    
    try {
        const dataPackage = {
            deviceInfo: {
                deviceName: 'ESP8266 NodeMCU Soil Monitor',
                uploadTime: new Date().toISOString(),
                recordCount: dataHistory.length,
                version: '1.0'
            },
            thresholds: MOISTURE_THRESHOLDS,
            data: dataHistory.map(record => ({
                timestamp: record.timestamp.toISOString(),
                moisture: record.moisture,
                moistureRaw: record.moistureRaw,
                status: record.status
            }))
        };
        
        dataPackage.dataHash = generateSecureHash(dataPackage.data);
        const encryptedData = encryptData(dataPackage, encryptionKey);
        
        const payload = {
            encryptedData: encryptedData,
            metadata: {
                deviceType: 'esp8266-nodemcu-soil-monitor',
                uploadTime: new Date().toISOString(),
                recordCount: dataHistory.length,
                encrypted: true
            }
        };
        
        const response = await fetch('https://api.jsonbin.io/v3/b', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Master-Key': '$2a$10$6IQuPr5QuS2W8dYemf5TpeWNK1nK5de1bnMNv.ZHKdAos63DwnNlu',
                'X-Bin-Name': `SOILMONITOR${Date.now()}`
            },
            body: JSON.stringify(payload)
        });
        
        if (response.ok) {
            const result = await response.json();
            const binId = result.metadata.id;
            
            showCloudStatus(`✓ Uploaded successfully! Bin ID: ${binId}`, 'success');
            addToConsole(`Cloud upload successful - Bin ID: ${binId}`);
            
            setTimeout(() => {
                showCloudStatus(`Use Bin ID "${binId}" and your password to retrieve data`, 'success');
            }, 3000);
            
        } else {
            throw new Error(`Upload failed: ${response.status} ${response.statusText}`);
        }
        
    } catch (error) {
        console.error('Cloud upload error:', error);
        
        if (error.message.includes('jsonbin')) {
            showCloudStatus('Get free API key from jsonbin.io first', 'error');
        } else {
            showCloudStatus('Upload failed: ' + error.message, 'error');
        }
        addToConsole('Cloud upload failed: ' + error.message);
    } finally {
        uploadToCloudBtn.classList.remove('uploading');
        uploadToCloudBtn.disabled = false;
        updateUploadButtonState();
    }
}

function showCloudStatus(message, type) {
    cloudStatusDiv.innerHTML = '';
    
    const statusSpan = document.createElement('span');
    statusSpan.className = `cloud-status cloud-${type}`;
    statusSpan.textContent = message;
    
    cloudStatusDiv.appendChild(statusSpan);
    
    if (type === 'success') {
        setTimeout(() => {
            cloudStatusDiv.innerHTML = '';
        }, 10000);
    } else if (type === 'error') {
        setTimeout(() => {
            cloudStatusDiv.innerHTML = '';
        }, 5000);
    }
}

async function connectToESP8266() {
    esp8266Ip = esp8266IpInput.value.trim();
    
    if (!esp8266Ip) {
        alert('Please enter ESP8266 IP address');
        return;
    }
    
    // Validate IP format (basic check)
    const ipPattern = /^(\d{1,3}\.){3}\d{1,3}$/;
    if (!ipPattern.test(esp8266Ip)) {
        alert('Please enter a valid IP address (e.g., 192.168.1.100)');
        return;
    }
    
    updateConnectionStatus('connecting');
    addToConsole(`Attempting to connect to ESP8266 NodeMCU at ${esp8266Ip}...`);
    
    try {
        // Test connection by fetching data
        const response = await fetch(`http://${esp8266Ip}/data`, {
            method: 'GET',
            mode: 'cors'
        });
        
        if (response.ok) {
            updateConnectionStatus('connected');
            connectBtn.disabled = true;
            disconnectBtn.disabled = false;
            printDataBtn.disabled = false;
            exportDataBtn.disabled = false;
            esp8266IpInput.disabled = true;
            
            startDataCollection();
            startFetchingData();
            
            addToConsole('Connected to ESP8266 NodeMCU successfully!');
            addToConsole('Fetching data every 2 seconds...');
            updateUploadButtonState();
        } else {
            throw new Error(`HTTP ${response.status}`);
        }
        
    } catch (error) {
        console.error('Connection failed:', error);
        addToConsole(`Connection failed: ${error.message}`);
        addToConsole('Make sure ESP8266 is powered on and on the same network');
        updateConnectionStatus('disconnected');
    }
}

function disconnectFromESP8266() {
    stopFetchingData();
    stopDataCollection();
    
    updateConnectionStatus('disconnected');
    connectBtn.disabled = false;
    disconnectBtn.disabled = true;
    printDataBtn.disabled = true;
    exportDataBtn.disabled = true;
    esp8266IpInput.disabled = false;
    
    addToConsole('Disconnected from ESP8266 NodeMCU');
    updateUploadButtonState();
}

function startFetchingData() {
    if (fetchInterval) {
        clearInterval(fetchInterval);
    }
    
    fetchInterval = setInterval(async () => {
        if (isConnected) {
            try {
                const response = await fetch(`http://${esp8266Ip}/data`, {
                    method: 'GET',
                    mode: 'cors'
                });
                
                if (response.ok) {
                    const data = await response.json();
                    processESP8266Data(data);
                } else {
                    throw new Error(`HTTP ${response.status}`);
                }
                
            } catch (error) {
                console.error('Fetch error:', error);
                addToConsole(`Fetch error: ${error.message}`);
                
                // Disconnect on repeated failures
                if (error.message.includes('Failed to fetch')) {
                    addToConsole('Lost connection to ESP8266 NodeMCU');
                    disconnectFromESP8266();
                }
            }
        }
    }, FETCH_INTERVAL);
}

function stopFetchingData() {
    if (fetchInterval) {
        clearInterval(fetchInterval);
        fetchInterval = null;
    }
}

function processESP8266Data(data) {
    try {
        // Use the moisture percentage directly from ESP8266
        const moisturePercent = parseInt(data.moisture) || 0;
        const moistureRaw = parseInt(data.moistureRaw) || 0;
        
        let status = 'Unknown';
        let led1 = data.led1 || false;
        let led2 = data.led2 || false;
        let led3 = data.led3 || false;
        
        // Determine status based on raw value (matching ESP8266 logic)
        if (moistureRaw >= MOISTURE_THRESHOLDS.DRY) {
            status = 'Dry';
        } else if (moistureRaw >= MOISTURE_THRESHOLDS.MEDIUM_LOW && moistureRaw < MOISTURE_THRESHOLDS.MEDIUM_HIGH) {
            status = 'Medium';
        } else if (moistureRaw < MOISTURE_THRESHOLDS.WET) {
            status = 'Wet';
        }
        
        // Update dashboard display 
        updateDashboardDisplay({
            moisture: moisturePercent,
            moistureRaw: moistureRaw,
            status: status,
            led1: led1,
            led2: led2,
            led3: led3,
            timestamp: Date.now()
        });
        
        // Store current data
        currentData = {
            moisture: moisturePercent,
            moistureRaw: moistureRaw,
            status: status,
            timestamp: new Date()
        };
        
    } catch (error) {
        console.error('Data processing error:', error);
        addToConsole('Parse error: ' + error.message);
    }
}

function updateDashboardDisplay(data) {
    // Update moisture display
    document.getElementById('moistureValue').textContent = data.moisture;
    document.getElementById('moistureRaw').textContent = data.moistureRaw;
    document.getElementById('moistureFill').style.height = Math.min(Math.max(data.moisture, 0), 100) + '%';
    document.getElementById('moistureTime').textContent = new Date().toLocaleTimeString();
    
    // Update status
    updateStatus(data.status);
    
    // Update LEDs
    updateLEDs(data.led1, data.led2, data.led3);
}

function startDataCollection() {
    if (dataInterval) {
        clearInterval(dataInterval);
    }
    
    dataInterval = setInterval(() => {
        if (currentData && isConnected) {
            const recordToSave = {
                timestamp: new Date(),
                moisture: currentData.moisture,
                moistureRaw: currentData.moistureRaw,
                status: currentData.status
            };
            
            // Always save data every 5 seconds
            dataHistory.unshift(recordToSave);
            
            if (dataHistory.length > MAX_HISTORY) {
                dataHistory = dataHistory.slice(0, MAX_HISTORY);
            }
            
            updateDataTable();
            updateRecordCount();
            updateUploadButtonState();
            
            addToConsole(`✓ Data saved: ${recordToSave.moisture}% (${recordToSave.status}) - Raw: ${recordToSave.moistureRaw}`);
        }
    }, DATA_SAVE_INTERVAL);
    
    addToConsole('Data collection started (every 5 seconds)');
}

function stopDataCollection() {
    if (dataInterval) {
        clearInterval(dataInterval);
        dataInterval = null;
    }
    addToConsole('Data collection stopped');
}

function updateRecordCount() {
    document.getElementById('recordCount').textContent = dataHistory.length;
}

function updateConnectionStatus(status) {
    const statusEl = document.getElementById('connectionStatus');
    statusEl.classList.remove('status-connected', 'status-disconnected', 'status-connecting', 'pulse');
    
    switch(status) {
        case 'connected':
            statusEl.classList.add('status-connected');
            statusEl.textContent = 'Connected';
            isConnected = true;
            break;
        case 'disconnected':
            statusEl.classList.add('status-disconnected');
            statusEl.textContent = 'Disconnected';
            isConnected = false;
            break;
        case 'connecting':
            statusEl.classList.add('status-connecting', 'pulse');
            statusEl.textContent = 'Connecting...';
            break;
    }
}

function updateLEDs(led1, led2, led3) {
    const led1El = document.getElementById('led1');
    const led2El = document.getElementById('led2');
    const led3El = document.getElementById('led3');
    
    // Reset all LEDs
    led1El.className = 'led-indicator led-1';
    led2El.className = 'led-indicator led-2';
    led3El.className = 'led-indicator led-3';
    
    // Turn on appropriate LEDs
    if (led1) led1El.classList.add('led-on');
    if (led2) led2El.classList.add('led-on');
    if (led3) led3El.classList.add('led-on');
}

function updateStatus(status) {
    const statusElement = document.getElementById('statusIndicator');
    statusElement.textContent = status;
    
    statusElement.classList.remove('status-dry', 'status-medium', 'status-wet', 'status-unknown');
    
    switch(status) {
        case 'Dry':
            statusElement.classList.add('status-dry');
            break;
        case 'Medium':
            statusElement.classList.add('status-medium');
            break;
        case 'Wet':
            statusElement.classList.add('status-wet');
            break;
        default:
            statusElement.classList.add('status-unknown');
    }
}

function updateDataTable() {
    const tableBody = document.getElementById('dataTable');
    
    if (dataHistory.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No data collected yet...</td></tr>';
        return;
    }
    
    let tableHTML = '';
    
    dataHistory.forEach((record, index) => {
        let statusClass = '';
        switch(record.status) {
            case 'Dry':
                statusClass = 'text-danger fw-bold';
                break;
            case 'Medium':
                statusClass = 'text-warning fw-bold';
                break;
            case 'Wet':
                statusClass = 'text-success fw-bold';
                break;
        }
        
        const rowClass = index < 3 ? 'table-info' : '';
        
        tableHTML += `
            <tr class="${rowClass}">
                <td>${record.timestamp.toLocaleTimeString()}</td>
                <td>${record.moisture}%</td>
                <td>${record.moistureRaw}</td>
                <td class="${statusClass}">${record.status}</td>
            </tr>
        `;
    });
    
    tableBody.innerHTML = tableHTML;
}

function addToConsole(message) {
    const timestamp = new Date().toLocaleTimeString();
    const newLine = document.createElement('div');
    newLine.textContent = `[${timestamp}] ${message}`;
    consoleDiv.appendChild(newLine);
    
    consoleDiv.scrollTop = consoleDiv.scrollHeight;
    
    const lines = consoleDiv.children;
    if (lines.length > 50) {
        consoleDiv.removeChild(lines[0]);
    }
}

function exportData() {
    if (dataHistory.length === 0) {
        alert('No data available to export');
        return;
    }
    
    const exportData = {
        deviceInfo: {
            deviceName: 'ESP8266 NodeMCU Soil Monitor',
            exportTime: new Date().toISOString(),
            recordCount: dataHistory.length,
            version: '1.0'
        },
        thresholds: MOISTURE_THRESHOLDS,
        data: dataHistory.map(record => ({
            timestamp: record.timestamp.toISOString(),
            moisture: record.moisture,
            moistureRaw: record.moistureRaw,
            status: record.status
        }))
    };
    
    const blob = new Blob([JSON.stringify(exportData, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    
    const a = document.createElement('a');
    a.href = url;
    a.download = `esp8266-soil-data-${new Date().toISOString().split('T')[0]}.json`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    
    addToConsole(`Data exported: ${dataHistory.length} records`);
}

function printCurrentData() {
    if (dataHistory.length === 0) {
        alert('No data available to print');
        return;
    }
    
    const latestData = currentData || dataHistory[0];
    const printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>ESP8266 NodeMCU Soil Moisture Data Report</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                .header { text-align: center; margin-bottom: 30px; }
                .data-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 30px 0; }
                .data-item { padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
                .data-label { font-weight: bold; color: #666; }
                .data-value { font-size: 24px; margin: 5px 0; }
                .status { padding: 5px 10px; border-radius: 15px; color: white; display: inline-block; }
                .status-dry { background-color: #dc3545; }
                .status-medium { background-color: #ffc107; color: black; }
                .status-wet { background-color: #28a745; }
                .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                .table th { background-color: #f8f9fa; }
                .threshold-info { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
                @media print { 
                    body { margin: 20px; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>ESP8266 NodeMCU Soil Moisture Report</h1>
                <p>Generated on: ${new Date().toLocaleString()}</p>
                <p>Total Records: ${dataHistory.length}</p>
            </div>
            
            <div class="threshold-info">
                <h4>Moisture Level Thresholds</h4>
                <ul>
                    <li><strong>Dry:</strong> Raw value ≥ 1000</li>
                    <li><strong>Medium:</strong> Raw value 675-949</li>
                    <li><strong>Wet:</strong> Raw value < 675</li>
                </ul>
            </div>
            
            <div class="data-grid">
                <div class="data-item">
                    <div class="data-label">Current Soil Moisture</div>
                    <div class="data-value">${latestData.moisture}%</div>
                    <div>Raw Value: ${latestData.moistureRaw}</div>
                    <div class="status status-${latestData.status.toLowerCase()}">${latestData.status}</div>
                </div>
                
                <div class="data-item">
                    <div class="data-label">Data Collection</div>
                    <div class="data-value">Every 5 seconds</div>
                    <div>Last Reading: ${latestData.timestamp.toLocaleString()}</div>
                </div>
            </div>
            
            <h3>Recent Data History (${dataHistory.length} records)</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Moisture (%)</th>
                        <th>Raw Value</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    ${dataHistory.map(record => `
                        <tr>
                            <td>${record.timestamp.toLocaleString()}</td>
                            <td>${record.moisture}%</td>
                            <td>${record.moistureRaw}</td>
                            <td>${record.status}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
            
            <div class="no-print" style="margin-top: 20px; text-align: center;">
                <button onclick="window.print()">Print This Report</button>
                <button onclick="window.close()">Close</button>
            </div>
        </body>
        </html>
    `;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    setTimeout(() => {
        printWindow.print();
    }, 500);
}

document.addEventListener('visibilitychange', function() {
    if (document.hidden && isConnected) {
        addToConsole('Page hidden - data collection continues');
    } else if (!document.hidden && isConnected) {
        addToConsole('Page visible - monitoring active');
        updateRecordCount(); 
    }
});

</script>

</body>
</html>