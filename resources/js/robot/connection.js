let currentConnection = null;
let ws = null;

window.showLoginModal = function() {
    const modal = document.getElementById('accessModal');
    modal.classList.remove('hidden');
    modal.classList.remove('scale-95');
    modal.classList.add('scale-110');
    setTimeout(() => {
        modal.classList.remove('scale-110');
        modal.classList.add('scale-100');
    }, 120);
    document.getElementById('modalBackdrop').classList.remove('hidden');
};


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

            // Use proxy route instead of direct HTTP request
            const response = await fetch(`/robot/proxy?target=${ip}`);
            const data = await response.json();
            
            if (!data.success) {
                throw new Error("Cannot reach ESP32");
            }

            // Store IP for future requests
            window.esp32IP = ip;
            
            // Initialize WebSocket if needed
            initWebSocket(ip);
        }

        // Show success state
        spinner.classList.add('hidden');
        success.classList.remove('hidden');
        disconnectBtn.classList.remove('hidden');

        currentConnection = method;

    } catch (err) {
        console.error(err);
        alert(err.message);
        overlay.classList.add('hidden');
    }
};

// Update command function to use proxy
function sendCommand(command) {
    if (!window.esp32IP) {
        alert("Please connect to the ESP32 first.");
        return;
    }

    fetch(`/robot/proxy?target=${window.esp32IP}&command=${command}`)
        .then(response => response.json())
        .then(data => {
            if (!data.success) throw new Error(data.error);
            console.log(`Command sent: ${command}`);
        })
        .catch(error => {
            console.error(error);
            alert("Command failed. Check ESP32 connection.");
        });
}

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
        const apiKeyInput = document.getElementById('api-key');
        if (apiKeyInput) {
            apiKeyInput.value = data.api_key || 'No API key generated';
        }
        const regenerateBtn = document.getElementById('regenerate-api');
        const nextResetSpan = document.getElementById('next-reset');
        if (regenerateBtn) {
            if (!data.can_reset) {
                regenerateBtn.disabled = true;
                regenerateBtn.classList.add('opacity-50');
            } else {
                regenerateBtn.disabled = false;
                regenerateBtn.classList.remove('opacity-50');
            }
        }
        if (nextResetSpan) {
            nextResetSpan.textContent = data.next_reset ? `Next reset available: ${new Date(data.next_reset).toLocaleDateString()}` : '';
        }
    } catch (error) {
        console.error('Failed to load API key:', error);
    }
}

// Add clipboard functionality
window.copyToClipboard = async function() {
    const apiKey = document.getElementById('api-key').value;
    try {
        await navigator.clipboard.writeText(apiKey);
        // Update Alpine.js state through global event
        window.dispatchEvent(new CustomEvent('api-copied', { detail: true }));
        setTimeout(() => {
            window.dispatchEvent(new CustomEvent('api-copied', { detail: false }));
        }, 2000);
    } catch (err) {
        alert('Failed to copy API key to clipboard');
    }
};

// Update regenerateApiKey function
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
        // Update API key display
        const apiKeyInput = document.getElementById('api-key');
        if (apiKeyInput) {
            apiKeyInput.value = data.api_key;
        }
        // Show success state
        const nextResetSpan = document.getElementById('next-reset');
        if (nextResetSpan && data.next_reset) {
            nextResetSpan.textContent = `Next reset available: ${new Date(data.next_reset).toLocaleDateString()}`;
        }
    } catch (error) {
        alert('Failed to regenerate API key');
    }
};