<div class="p-6 mb-7">
    <h2 class="text-xl font-bold text-center dark:text-white">Configure Your Robot</h2>
    <p class="text-center text-gray-600 dark:text-gray-300 mb-6">Choose your controller and add components:</p>
    <div x-data="robotBuilder()" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-3">
        <div class="mx-auto justify-start space-y-6 p-2 lg:p-7">

            <!-- Controller Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Controller Type</label>
                <select x-model="selectedController"
                        @change="handleControllerChange"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    <option value="">Select Controller</option>
                    <option value="ESP32">ESP32</option>
                    <option value="ESP8266">ESP8266</option>
                </select>
            </div>

            <!-- Components Section -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Components</label>

                <!-- Add Component Button -->
                <button x-show="!isAddingComponent"
                        @click="startAddComponent"
                        :disabled="!selectedController"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                    Add Component
                </button>

                <!-- Add Component Form -->
                <div x-show="isAddingComponent" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mt-3">
                    <div class="space-y-4">

                        <!-- Component Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Component Type</label>
                            <select x-model="tempComponent.type"
                                    @change="handleComponentTypeChange"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Select Component</option>
                                <template x-for="type in availableComponents" :key="type">
                                    <option :value="type" x-text="type"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Pin Selection -->
                        <template x-if="tempComponent.type">
                            <div class="grid grid-cols-2 gap-4">
                                <template x-for="pin in getPinConfig(tempComponent.type)" :key="pin.name">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                                               x-text="pin.name"></label>
                                        <select x-model="tempComponent.pins[pin.name]"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            <option value="">Select Pin</option>
                                            <template x-for="pinOpt in getAvailablePins()" :key="pinOpt">
                                                <option :value="pinOpt" x-text="pinOpt"></option>
                                            </template>
                                        </select>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <!-- Validation Message -->
                        <p x-show="validationMessage" x-text="validationMessage"
                           class="text-sm text-red-600"></p>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-2">
                            <button @click="cancelAddComponent"
                                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                                Cancel
                            </button>
                            <button @click="confirmAddComponent"
                                    :disabled="!tempComponent.type"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- List of Components Added -->
            <div class="space-y-4">
                <template x-for="(component, index) in components" :key="index">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-medium text-gray-900 dark:text-white" x-text="component.type"></h3>
                            <button @click="removeComponent(index)"
                                    class="text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <template x-for="(pinValue, pinName) in component.pins" :key="pinName">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                                           x-text="pinName"></label>
                                    <input type="text" x-model="component.pins[pinName]"
                                           class="bg-gray-100 dark:bg-gray-800 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:border-gray-600 dark:text-white" readonly />
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Public Checkbox & Save -->
            <div class="flex gap-4 mt-7 mb-3 space-x-4 justify-between items-center">
                <div class="flex items-center">
                    <button id="skip-button" 
                            @click="window.navigateToTab(2)"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                        Skip
                    </button>
                </div>
                <div class="p-1 flex justify-end gap-4 items-center">
                    <div class="flex items-center">
                        <input type="checkbox" x-model="!isPublic" class="w-4 h-4 text-blue-600 rounded dark:bg-gray-300 border-gray-300 focus:ring-blue-500">
                        <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">Make this configuration private</label>
                    </div>
                    <button type="button" @click="saveConfiguration"
                            :disabled="!components.length"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.robotBuilder = function() {
        return {
            selectedController: '',
            isAddingComponent: false,
            tempComponent: { type: '', pins: {} },
            components: [],
            isPublic: false, // Changed default to false (private)
            validationMessage: '',
            componentTypes: [
                'Servo',
                'Ultrasonic Sensor',
                'Motor Driver',
                'LCD',
                'Push Button',
                'Infrared Sensor',
                'DFPlayer'
            ],
            pinConfigs: {
                'Servo': [{ name: 'Data' }],
                'Ultrasonic Sensor': [{ name: 'Trig' }, { name: 'Echo' }],
                'Motor Driver': [
                    { name: 'IN1' }, { name: 'IN2' }, { name: 'IN3' }, { name: 'IN4' }, 
                    { name: 'ENA', optional: true }, { name: 'ENB', optional: true }
                ],
                'LCD': [{ name: 'SDA' }, { name: 'SCL' }],
                'Push Button': [{ name: 'Data' }],
                'Infrared Sensor': [{ name: 'Data' }],
                'DFPlayer': [{ name: 'RX' }, { name: 'TX' }]
            },
            controllerPins: {
                'ESP32': Array.from({ length: 40 }, (_, i) => `GPIO${i}`),
                'ESP8266': ['D0', 'D1', 'D2', 'D3', 'D4', 'D5', 'D6', 'D7', 'D8', 'A0', 'RX', 'TX']
            },

            handleControllerChange() {
                this.components = [];
                this.tempComponent = { type: '', pins: {} };
                this.isAddingComponent = false;
                this.validationMessage = '';
            },

            getPinConfig(type) {
                return this.pinConfigs[type] || [];
            },

            getAvailablePins() {
                return this.controllerPins[this.selectedController] || [];
            },

            get availableComponents() {
                if (this.selectedController === 'ESP8266') {
                    // If ESP8266 does not support DFPlayer, remove it
                    return this.componentTypes.filter(type => type !== 'DFPlayer');
                }
                return this.componentTypes;
            },

            startAddComponent() {
                this.isAddingComponent = true;
                this.tempComponent = { type: '', pins: {} };
                this.validationMessage = '';
            },

            cancelAddComponent() {
                this.isAddingComponent = false;
                this.tempComponent = { type: '', pins: {} };
                this.validationMessage = '';
            },

            handleComponentTypeChange() {
                if (!this.tempComponent.type) return;
                this.tempComponent.pins = {};
                this.getPinConfig(this.tempComponent.type).forEach(pin => {
                    this.tempComponent.pins[pin.name] = '';
                });
            },

            confirmAddComponent() {
                // Check all pins are chosen
                if (Object.values(this.tempComponent.pins).some(pin => !pin)) {
                    this.validationMessage = 'Please select all pins';
                    return;
                }
                // Check for duplicates
                if (this.isPinUsed(this.tempComponent.pins)) {
                    this.validationMessage = 'One or more pins already in use';
                    return;
                }
                this.components.push(JSON.parse(JSON.stringify(this.tempComponent)));
                this.cancelAddComponent();
            },

            isPinUsed(pinObj) {
                const pins = Object.values(pinObj);
                return pins.some(pin =>
                    this.components.some(comp =>
                        Object.values(comp.pins).includes(pin)
                    )
                );
            },

            removeComponent(idx) {
                this.components.splice(idx, 1);
            },

            async saveConfiguration() {
                try {
                    const response = await fetch('/api/robot/configuration', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({
                            controller: this.selectedController,
                            components: this.components,
                            isPublic: !this.isPublic // Invert the value when sending to backend
                        })
                    });

                    if (!response.ok) throw new Error('Failed to save configuration');

                    alert('Configuration saved successfully!');
                    window.navigateToTab(2);
                } catch (error) {
                    console.error('Error saving configuration:', error);
                    alert('Failed to save configuration');
                }
            },

            init() {
                this.loadSavedConfiguration();
            },

            async loadSavedConfiguration() {
                try {
                    const response = await fetch('/api/robot/configuration');
                    if (!response.ok) return;

                    const { data } = await response.json();
                    if (!data) return;

                    this.selectedController = data.controller;
                    const components = [];
                    
                    for (const [type, pins] of Object.entries(data.component)) {
                        const pinConfig = this.getPinConfig(type);
                        const pinValues = pins.split(',');
                        
                        const componentPins = {};
                        pinConfig.forEach((config, index) => {
                            componentPins[config.name] = pinValues[index] || '';
                        });

                        components.push({
                            type: type,
                            pins: componentPins
                        });
                    }

                    this.components = components;
                    this.isPublic = data.show;

                } catch (error) {
                    console.error('Error loading configuration:', error);
                }
            }
        };
    };
</script>