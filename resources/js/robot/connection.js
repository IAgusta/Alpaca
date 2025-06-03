let currentConnection = null;
let ws = null;

window.addEventListener("DOMContentLoaded", () => {
    ['wifi', 'api'].forEach(type => {
        document.getElementById(`${type}-overlay`).classList.add('hidden');
        document.getElementById(`${type}-spinner`).classList.add('hidden');
        document.getElementById(`${type}-success`).classList.add('hidden');
        document.getElementById(`${type}-disconnect`).classList.add('hidden');
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

function expandCard(type) {
    const card = document.getElementById(`${type}-card`);
    const details = card.querySelector('.details');
    
    if (card.classList.contains('disabled')) return;
    
    // Collapse other cards
    ['wifi', 'api'].forEach((c) => {
        const otherCard = document.getElementById(`${c}-card`);
        const otherDetails = otherCard.querySelector('.details');
        if (c !== type) {
            otherCard.classList.remove('expanded');
            otherDetails.classList.add('hidden');
        }
    });
    
    // Toggle selected card
    const expanded = card.classList.toggle('expanded');
    details.classList.toggle('hidden', !expanded);
}

async function connectToESP32(method) {
    const overlay = document.getElementById(`${method}-overlay`);
    const spinner = document.getElementById(`${method}-spinner`);
    const success = document.getElementById(`${method}-success`);
    const disconnectBtn = document.getElementById(`${method}-disconnect`);
    const card = document.getElementById(`${method}-card`);

    overlay.classList.remove('hidden');
    spinner.classList.remove('hidden');
    success.classList.add('hidden');
    disconnectBtn.classList.add('hidden');

    try {
        let response;
        if (method === 'wifi') {
            const ip = document.getElementById('wifi-ip').value.trim();
            if (!ip.match(/^(\d{1,3}\.){3}\d{1,3}$/)) throw new Error("Invalid IP format");
            response = await fetch(`http://${ip}/connect`);
        } else if (method === 'api') {
            const apiKey = document.getElementById('api-key').value.trim();
            if (!apiKey) throw new Error("API key is required");
            response = await fetch('/api/robot/connect', {
                headers: {
                    'Authorization': `Bearer ${apiKey}`,
                    'Accept': 'application/json'
                }
            });
        }

        if (!response.ok) throw new Error("Connection failed");

        spinner.classList.add('hidden');
        success.classList.remove('hidden');
        disconnectBtn.classList.remove('hidden');

        currentConnection = method;
        
        // Disable other card
        const other = method === 'wifi' ? 'api' : 'wifi';
        document.getElementById(`${other}-card`).classList.add('disabled');

    } catch (err) {
        alert("Connection failed: " + err.message);
        overlay.classList.add('hidden');
    }
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

async function regenerateApiKey() {
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
        
        await loadApiKey();
    } catch (error) {
        alert('Failed to regenerate API key');
    }
}

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