let currentConnection = null;

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

    // Only initialize API connection if API key exists and user is authenticated
    const apiKey = document.getElementById('api-key')?.value;
    const isAuthenticated = document.body.classList.contains('auth-user');
    if (isAuthenticated && apiKey && apiKey !== 'No API key generated') {
        window.robotConnection = {
            mode: 'api',
            key: apiKey,
            active: true
        };
    }

    // Attach disconnect handlers for both wifi and api cards
    ['wifi', 'api'].forEach(type => {
        const disconnectBtn = document.getElementById(`${type}-disconnect`);
        if (disconnectBtn) {
            disconnectBtn.onclick = () => disconnect(type);
        }
    });
});

// Cleaned up connectToESP32
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

            // Test connection through proxy
            const response = await fetch(`/robot/proxy?target=${ip}`);
            const data = await response.json();
            
            if (!data.success) {
                throw new Error("Cannot reach ESP32");
            }

            // Store connection info and disable API mode if it was active
            window.robotConnection = {
                mode: 'proxy',
                target: ip,
                active: true
            };

            // Store IP Address when successfully connected via Proxy
            saveESP32IP(ip);

            // Disable API card if it exists
            const apiCard = document.getElementById('api-card');
            if (apiCard) {
                apiCard.classList.add('opacity-50', 'pointer-events-none');
            }
            
            // *** Navigate to controller tab if user is authenticated ***
            const isAuthenticated = document.body.classList.contains('auth-user');
            if (isAuthenticated && typeof window.navigateToTab === 'function') {
                setTimeout(() => {
                    window.navigateToTab(3);
                }, 1000); // 1000ms = 1 second delay
            }

        } else if (method === 'api') {
            const apiKey = document.getElementById('api-key')?.value;
            
            if (!apiKey || apiKey === 'No API key generated') {
                throw new Error("Invalid API key");
            }

            window.robotConnection = {
                mode: 'api',
                key: apiKey,
                active: true
            };

            // Navigate to control tab after API connection
            window.navigateToTab?.(3);

            // Disable WiFi card
            const wifiCard = document.getElementById('wifi-card');
            if (wifiCard) {
                wifiCard.classList.add('opacity-50', 'pointer-events-none');
            }
        }

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

function saveESP32IP(ip) {
    const ipData = {
        ip,
        savedAt: Date.now() // timestamp in milliseconds
    };
    localStorage.setItem('esp32IP', JSON.stringify(ipData));
}

function getSavedESP32IP(maxAgeHours = 12) {
    const item = localStorage.getItem('esp32IP');
    if (!item) return null;
    try {
        const data = JSON.parse(item);
        const age = (Date.now() - data.savedAt) / (1000 * 60 * 60); // hours
        if (age <= maxAgeHours) {
            return data.ip;
        } else {
            localStorage.removeItem('esp32IP');
            return null;
        }
    } catch {
        localStorage.removeItem('esp32IP');
        return null;
    }
}

// Improved disconnect button logic (fixes cards and connection state)
function disconnect(method) {
    const overlay = document.getElementById(`${method}-overlay`);
    const spinner = document.getElementById(`${method}-spinner`);
    const success = document.getElementById(`${method}-success`);
    const disconnectBtn = document.getElementById(`${method}-disconnect`);
    const input = document.getElementById(`${method}-${method === 'wifi' ? 'ip' : 'key'}`);

    overlay?.classList.add('hidden');
    spinner?.classList.add('hidden');
    success?.classList.add('hidden');
    disconnectBtn?.classList.add('hidden');

    if (input) {
        input.style.display = 'block';
        input.value = '';
    }

    // Enable both cards
    ['wifi-card', 'api-card'].forEach(id => {
        const card = document.getElementById(id);
        if (card) {
            card.classList.remove('opacity-50', 'pointer-events-none');
        }
    });

    // Remove connection state
    window.robotConnection = null;
    currentConnection = null;
}

function sendCommand(command) {
    if (!window.robotConnection?.active) {
        if (window.robotConnection?.mode === 'api' && window.robotConnection?.key) {
            window.robotConnection.active = true;
        } else {
            alert("Please connect to the robot first.");
            return;
        }
    }

    if (window.robotConnection.mode === 'proxy') {
        fetch(`/robot/proxy?target=${window.robotConnection.target}&command=${command}`)
            .then(response => response.json())
            .then(data => {
                if (!data.success) throw new Error(data.error);
                console.log(`Command sent: ${command}`);
            })
            .catch(error => {
                console.error(error);
                alert("Command failed. Check robot connection.");
            });
    } else {
        // API mode
        fetch(`/api/robot/command`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${window.robotConnection.key}`
            },
            body: JSON.stringify({ command })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) throw new Error(data.error);
            console.log(`Command sent via API: ${command}`);
        })
        .catch(error => {
            console.error(error);
            alert("API command failed. Check your connection.");
        });
    }
}

async function loadApiKey() {
    try {
        const response = await fetch('/robot/key');  // Updated path
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

// Clipboard & regenerate API key handlers remain unchanged
window.copyToClipboard = async function() {
    const apiKey = document.getElementById('api-key').value;
    try {
        await navigator.clipboard.writeText(apiKey);
        window.dispatchEvent(new CustomEvent('api-copied', { detail: true }));
        setTimeout(() => {
            window.dispatchEvent(new CustomEvent('api-copied', { detail: false }));
        }, 2000);
    } catch (err) {
        alert('Failed to copy API key to clipboard');
    }
};

window.regenerateApiKey = async function() {
    try {
        const response = await fetch('/robot/generate-key', {
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
        const apiKeyInput = document.getElementById('api-key');
        if (apiKeyInput) {
            apiKeyInput.value = data.api_key;
        }
        const nextResetSpan = document.getElementById('next-reset');
        if (nextResetSpan && data.next_reset) {
            nextResetSpan.textContent = `Next reset available: ${new Date(data.next_reset).toLocaleDateString()}`;
        }
    } catch (error) {
        alert('Failed to regenerate API key');
    }
};