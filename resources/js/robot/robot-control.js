let esp32IP = null;
const ipPattern = /^(25[0-5]|2[0-4]\d|1\d\d|\d\d?)\.(25[0-5]|2[0-4]\d|1\d\d|\d\d?)\.(25[0-5]|2[0-4]\d|1\d\d|\d\d?)\.(25[0-5]|2[0-4]\d|1\d\d|\d\d?)$/;
let commandInterval = null;

// Function to connect to ESP32
function connectToESP32() {
    console.log("Connect button clicked"); // Debugging statement
    const ipAddress = document.getElementById('ip-address').value;
    const connectButton = document.getElementById('connect-button');
    const connectionStatus = document.getElementById('connection-status');
    const robotControls = document.getElementById('robot-controls');

    // Validate IP address
    if (!ipPattern.test(ipAddress)) {
        connectionStatus.textContent = "Invalid IP (must be 0-255.0-255.0-255.0-255).";
        connectionStatus.classList.remove('hidden', 'text-green-600');
        connectionStatus.classList.add('text-red-600');
        return;
    }

    connectButton.disabled = true;
    connectButton.textContent = "Connecting...";

    // Set timeout to prevent infinite loading
    const timeout = setTimeout(() => {
        connectButton.disabled = false;
        connectButton.textContent = "Connect";
        connectionStatus.textContent = "Timeout: ESP32 unreachable!";
        connectionStatus.classList.remove('hidden', 'text-green-600');
        connectionStatus.classList.add('text-red-600');
    }, 5000);

    // Send IP to Laravel backend for storage
    fetch('/set-esp32-ip', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ esp32_ip: ipAddress })
    })
    .then(response => {
        console.log("Received response from /set-esp32-ip"); // Debugging statement
        return response.json();
    })
    .then(data => {
        if (data.esp32_ip) {
            esp32IP = data.esp32_ip; // Save IP in JS
            console.log("ESP32 IP stored in Laravel:", esp32IP);

            // Test connection to ESP32 via Laravel
            return fetch(`/robot/connect?ip=${esp32IP}`, { method: 'GET' });
        } else {
            throw new Error("Laravel did not store IP");
        }
    })
    .then(response => {
        console.log("Received response from /robot/connect"); // Debugging statement
        return response.json();
    })
    .then(data => {
        if (data.success) {
            clearTimeout(timeout); // Clear the timeout
            connectionStatus.textContent = `Connected to ${esp32IP}`;
            connectionStatus.classList.remove('hidden', 'text-red-600');
            connectionStatus.classList.add('text-green-600');
            robotControls.classList.remove('opacity-50', 'pointer-events-none');
            connectButton.disabled = false;
            connectButton.textContent = "Connect";
        } else {
            throw new Error("Failed to connect to ESP32");
        }
    })
    .catch(error => {
        console.error(error);
        connectionStatus.textContent = "Connection failed!";
        connectionStatus.classList.remove('hidden', 'text-green-600');
        connectionStatus.classList.add('text-red-600');
        connectButton.disabled = false;
        connectButton.textContent = "Connect";
    });
}

// Function to send commands to ESP32
function sendCommand(command) {
    if (!esp32IP) {
        alert("Please connect to the ESP32 first.");
        return;
    }

    fetch(`/robot/${command}?ip=${esp32IP}`, { method: 'GET' })
    .then(response => {
        if (response.ok) {
            console.log(`Command sent: ${command}`);
        } else {
            throw new Error(`Failed to send command: ${command}`);
        }
    })
    .catch(error => {
        console.error(error);
        alert("Command failed. Check ESP32 connection.");
    });
}

// Function to update motor speed
function updateMotorSpeed(value) {
    if (!esp32IP) {
        alert("Please connect to the ESP32 first.");
        return;
    }

    document.getElementById('motorSpeed').textContent = value;
    fetch(`/robot/speed?value=${value}&ip=${esp32IP}`, { method: 'GET' })
    .then(response => {
        if (response.ok) {
            console.log(`Motor speed set to ${value}`);
        } else {
            throw new Error(`Failed to set motor speed: ${value}`);
        }
    })
    .catch(error => {
        console.error(error);
        alert("Failed to update motor speed. Check ESP32 connection.");
    });
}

// Function to handle button press and release
function handleButtonPress(command) {
    sendCommand(command);
    commandInterval = setInterval(() => sendCommand(command), 1000); // Send command every second
}

function handleButtonRelease() {
    clearInterval(commandInterval);
    sendCommand('stop');
}

// Attach functions to window for HTML access
window.connectToESP32 = connectToESP32;
window.sendCommand = sendCommand;
window.updateMotorSpeed = updateMotorSpeed;
window.handleButtonPress = handleButtonPress;
window.handleButtonRelease = handleButtonRelease;

// Wrap event listeners in DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {
    const connectButton = document.getElementById('connect-button');
    if (connectButton) connectButton.addEventListener('click', connectToESP32);

    const robotControls = [
        { id: 'forward-button', command: 'forward' },
        { id: 'left-button', command: 'left' },
        { id: 'stop-button', command: 'stop' },
        { id: 'right-button', command: 'right' },
        { id: 'backward-button', command: 'backward' }
    ];
    robotControls.forEach(btn => {
        const el = document.getElementById(btn.id);
        if (!el) return;
        el.addEventListener('mousedown', () => handleButtonPress(btn.command));
        el.addEventListener('mouseup', handleButtonRelease);
        el.addEventListener('touchstart', () => handleButtonPress(btn.command));
        el.addEventListener('touchend', handleButtonRelease);
    });

    const motorSlider = document.getElementById('motorSlider');
    if (motorSlider) {
        motorSlider.addEventListener('input', (event) => {
            updateMotorSpeed(event.target.value);
        });
    }
});