let currentConnection = null;
let ws = null;

window.addEventListener("DOMContentLoaded", () => {
    ['wifi', 'api'].forEach(type => {
        const overlay = document.getElementById(`${type}-overlay`);
        const spinner = document.getElementById(`${type}-spinner`);
        const success = document.getElementById(`${type}-success`);
        const disconnect = document.getElementById(`${type}-disconnect`);
        
        if (overlay && spinner && success && disconnect) {
            overlay.classList.add('hidden');
            spinner.classList.add('hidden');
            success.classList.add('hidden');
            disconnect.classList.add('hidden');
        }
    });

    if (document.getElementById('api-card')) {
        loadApiKey();
    }
});

function initWebSocket(ip) {
    ws = new WebSocket(`ws://${ip}:81`);
    
    ws.onopen = function() {
        console.log('Connected to ESP32 WebSocket');
    };
    
    ws.onmessage = function(evt) {
        const data = JSON.parse(evt.data);
        handleRobotResponse(data);
    };
    
    ws.onclose = function() {
        console.log('WebSocket connection closed');
        setTimeout(function() {
            initWebSocket(ip);
        }, 2000);
    };
}

function handleRobotResponse(data) {
    if (data.type === 'sensor') {
        updateSensorDisplay(data);
    } else if (data.type === 'status') {
        updateRobotStatus(data);
    }
}

window.connectToESP32 = async function(method) {
    const overlay = document.getElementById(`${method}-overlay`);
    const spinner = document.getElementById(`${method}-spinner`);
    const success = document.getElementById(`${method}-success`);
    const disconnectBtn = document.getElementById(`${method}-disconnect`);

    try {
        overlay.classList.remove('hidden');
        spinner.classList.remove('hidden');
        success.classList.add('hidden');
        disconnectBtn.classList.add('hidden');

        if (method === 'wifi') {
            const ip = document.getElementById('wifi-ip').value.trim();
            if (!ip.match(/^(\d{1,3}\.){3}\d{1,3}$/)) {
                throw new Error("Invalid IP format");
            }

            // Test connection to ESP32
            const testResponse = await fetch(`http://${ip}/status`);
            if (!testResponse.ok) {
                throw new Error("Cannot reach ESP32");
            }

            // Initialize WebSocket connection
            initWebSocket(ip);
            
        } else if (method === 'api') {
            const apiKey = document.getElementById('api-key').value.trim();
            if (!apiKey) throw new Error("API key is required");

            const response = await fetch('/api/robot/connect', {
                headers: {
                    'Authorization': `Bearer ${apiKey}`,
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) {
                throw new Error("Invalid API key");
            }
        }

        // Show success state
        spinner.classList.add('hidden');
        success.classList.remove('hidden');
        disconnectBtn.classList.remove('hidden');

        // Disable other connection method
        const other = method === 'wifi' ? 'api' : 'wifi';
        document.getElementById(`${other}-card`).classList.add('disabled');

        currentConnection = method;

    } catch (err) {
        console.error(err);
        alert(err.message);
        overlay.classList.add('hidden');
    }
};

function disconnect(method) {
    const overlay = document.getElementById(`${method}-overlay`);
    const spinner = document.getElementById(`${method}-spinner`);
    const success = document.getElementById(`${method}-success`);
    const disconnectBtn = document.getElementById(`${method}-disconnect`);
    const input = document.getElementById(`${method}-${method === 'wifi' ? 'ip' : 'key'}`);

    overlay.classList.add('hidden');
    spinner.classList.add('hidden');
    success.classList.add('hidden');
    disconnectBtn.classList.add('hidden');

    if (input) {
        input.style.display = 'block';
        input.value = '';
    }

    const other = method === 'wifi' ? 'api' : 'wifi';
    document.getElementById(`${other}-card`).classList.remove('disabled');

    currentConnection = null;
}

async function loadApiKey() {
    try {
        const response = await fetch('/api/robot/key');
        const data = await response.json();
        
        document.getElementById('api-key').value = data.api_key || 'No API key generated';
        
        const regenerateBtn = document.getElementById('regenerate-api');
        const nextResetSpan = document.getElementById('next-reset');
        
        if (!data.can_reset) {
            regenerateBtn.disabled = true;
            regenerateBtn.classList.add('opacity-50');
            nextResetSpan.textContent = `Next reset available: ${new Date(data.next_reset).toLocaleDateString()}`;
        } else {
            regenerateBtn.disabled = false;
            regenerateBtn.classList.remove('opacity-50');
            nextResetSpan.textContent = '';
        }
    } catch (error) {
        console.error('Failed to load API key:', error);
    }
}

window.regenerateApiKey = async function() {
    try {
        const response = await fetch('/api/robot/generate-key', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        if (data.error) {
            alert(data.error);
            return;
        }
        
        // Reload API key after generation
        await loadApiKey();
    } catch (error) {
        console.error(error);
        alert('Failed to regenerate API key');
    }
};

function copyApiKey() {
    const apiKeyInput = document.getElementById('api-key');
    apiKeyInput.select();
    document.execCommand('copy');
    alert('API key copied to clipboard!');
}

async function connectWithApiKey() {
    const apiKey = document.getElementById('api-key').value;
    if (!apiKey || apiKey === 'No API key generated') {
        alert('Please generate an API key first');
        return;
    }
    
    connectToESP32('api');
}