<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <h2 style="margin-bottom: 24px;">Connection Details</h2>
        <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 24px;">
            Enter the details from your WordPress site's <strong>web-manager Connector</strong> plugin.
        </p>

        <form action="/web-manager/public/index.php?route=sites&action=add" method="POST">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label for="name">Site Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="e.g. My Awesome Blog" required>
                </div>
                <div class="form-group">
                    <label for="url">Site URL</label>
                    <input type="url" name="url" id="url" class="form-control" placeholder="https://example.com" required>
                </div>
            </div>


        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label for="country">Country</label>
                <select name="country" id="country" class="form-control" onchange="updateStates()">
                    <option value="">Select Country</option>
                    <optgroup label="Popular">
                        <option value="Mexico">Mexico</option>
                        <option value="Spain">Spain</option>
                        <option value="Colombia">Colombia</option>
                        <option value="Argentina">Argentina</option>
                        <option value="USA">United States</option>
                    </optgroup>
                    <optgroup label="All Spanish Speaking">
                        <option value="Bolivia">Bolivia</option>
                        <option value="Chile">Chile</option>
                        <option value="Costa Rica">Costa Rica</option>
                        <option value="Cuba">Cuba</option>
                        <option value="Dominican Republic">Dominican Republic</option>
                        <option value="Ecuador">Ecuador</option>
                        <option value="El Salvador">El Salvador</option>
                        <option value="Guatemala">Guatemala</option>
                        <option value="Honduras">Honduras</option>
                        <option value="Nicaragua">Nicaragua</option>
                        <option value="Panama">Panama</option>
                        <option value="Paraguay">Paraguay</option>
                        <option value="Peru">Peru</option>
                        <option value="Puerto Rico">Puerto Rico</option>
                        <option value="Uruguay">Uruguay</option>
                        <option value="Venezuela">Venezuela</option>
                    </optgroup>
                    <optgroup label="Other">
                        <option value="Brazil">Brazil</option>
                        <option value="Canada">Canada</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="France">France</option>
                        <option value="Germany">Germany</option>
                        <option value="Italy">Italy</option>
                        <option value="Portugal">Portugal</option>
                        <option value="Other">Other (Type manually)</option>
                    </optgroup>
                </select>
            </div>
            <div class="form-group" id="state-container">
                <label for="state">State / Province</label>
                <select name="state" id="state" class="form-control" onchange="updateCities()" disabled>
                    <option value="">Select State</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group" id="city-container">
                <label for="city">City</label>
                <select name="city" id="city" class="form-control" disabled>
                    <option value="">Select City</option>
                </select>
            </div>
            <div class="form-group">
                <label for="specialty">Specialty</label>
                <select name="specialty" id="specialty" class="form-control">
                    <option value="">Select Specialty</option>
                    <optgroup label="General">
                        <option value="General Medicine">General Medicine</option>
                        <option value="Pediatrics">Pediatrics</option>
                        <option value="Obstetrics & Gynecology">Obstetrics & Gynecology</option>
                        <option value="Cardiology">Cardiology</option>
                    </optgroup>
                    <optgroup label="Specialized">
                        <option value="Dermatology">Dermatology</option>
                        <option value="Urology">Urology</option>
                        <option value="Dentistry">Dentistry</option>
                        <option value="Ophthalmology">Ophthalmology</option>
                        <option value="Otolaryngology">Otolaryngology</option>
                        <option value="Neurology">Neurology</option>
                        <option value="Endocrinology">Endocrinology</option>
                        <option value="Gastroenterology">Gastroenterology</option>
                        <option value="Psychiatry">Psychiatry</option>
                        <option value="Psychology">Psychology</option>
                        <option value="Physiotherapy">Physiotherapy</option>
                        <option value="Nutrition">Nutrition</option>
                        <option value="Radiology">Radiology</option>
                        <option value="Oncology">Oncology</option>
                        <option value="Hematology">Hematology</option>
                        <option value="Nephrology">Nephrology</option>
                        <option value="Rheumatology">Rheumatology</option>
                        <option value="Geriatrics">Geriatrics</option>
                        <option value="Traumatology">Traumatology</option>
                        <option value="General Surgery">General Surgery</option>
                        <option value="Plastic Surgery">Plastic Surgery</option>
                        <option value="Anesthesiology">Anesthesiology</option>
                    </optgroup>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-top: 16px;">
            <label for="api_key">API Key</label>
            <input type="text" name="api_key" id="api_key" class="form-control" required placeholder="Paste the key from the WP plugin">
        </div>

        <script>
        const locationData = {
            "Mexico": {
                "Aguascalientes": ["Aguascalientes", "Jesús María", "Calvillo"],
                "Baja California": ["Tijuana", "Mexicali", "Ensenada", "Rosarito"],
                "Baja California Sur": ["La Paz", "Los Cabos", "Loreto"],
                "Campeche": ["Campeche", "Ciudad del Carmen"],
                "Chiapas": ["Tuxtla Gutiérrez", "Tapachula", "San Cristóbal de las Casas"],
                "Chihuahua": ["Chihuahua", "Ciudad Juárez", "Delicias"],
                "Coahuila": ["Saltillo", "Torreón", "Monclova"],
                "Colima": ["Colima", "Manzanillo", "Villa de Álvarez"],
                "CDMX": ["Coyoacán", "Polanco", "Condesa", "Santa Fe", "Tlalpan", "Benito Juárez"],
                "Durango": ["Durango", "Gómez Palacio"],
                "Guanajuato": ["León", "Irapuato", "Celaya", "Guanajuato"],
                "Guerrero": ["Acapulco", "Chilpancingo", "Ixtapa Zihuatanejo"],
                "Hidalgo": ["Pachuca", "Tulancingo"],
                "Jalisco": ["Guadalajara", "Zapopan", "Tlaquepaque", "Puerto Vallarta", "Tlajomulco"],
                "México": ["Toluca", "Ecatepec", "Naucalpan", "Tlalnepantla"],
                "Michoacán": ["Morelia", "Uruapan", "Lázaro Cárdenas"],
                "Morelos": ["Cuernavaca", "Jiutepec"],
                "Nayarit": ["Tepic", "Bahía de Banderas"],
                "Nuevo León": ["Monterrey", "San Pedro Garza García", "Guadalupe", "San Nicolás"],
                "Oaxaca": ["Oaxaca de Juárez", "Salina Cruz", "Huatulco"],
                "Puebla": ["Puebla", "Tehuacán", "Cholula"],
                "Querétaro": ["Querétaro", "San Juan del Río"],
                "Quintana Roo": ["Cancún", "Playa del Carmen", "Cozumel", "Tulum"],
                "San Luis Potosí": ["San Luis Potosí", "Ciudad Valles"],
                "Sinaloa": ["Culiacán", "Mazatlán", "Los Mochis"],
                "Sonora": ["Hermosillo", "Ciudad Obregón", "Nogales"],
                "Tabasco": ["Villahermosa"],
                "Tamaulipas": ["Reynosa", "Matamoros", "Nuevo Laredo", "Tampico"],
                "Tlaxcala": ["Tlaxcala"],
                "Veracruz": ["Veracruz", "Boca del Río", "Xalapa", "Coatzacoalcos"],
                "Yucatán": ["Mérida", "Progreso", "Valladolid"],
                "Zacatecas": ["Zacatecas", "Fresnillo"]
            }
        };

        function updateStates() {
            const country = document.getElementById('country').value;
            const stateContainer = document.getElementById('state-container');
            const cityContainer = document.getElementById('city-container');

            if (country === "Mexico") {
                // Show Dropdown for Mexico
                stateContainer.innerHTML = '<label for="state">State / Province</label>' +
                    '<select name="state" id="state" class="form-control" onchange="updateCities()">' +
                    '<option value="">Select State</option></select>';
                
                const stateSelect = document.getElementById('state');
                for (const state in locationData["Mexico"]) {
                    const opt = document.createElement('option');
                    opt.value = state;
                    opt.textContent = state;
                    stateSelect.appendChild(opt);
                }

                cityContainer.innerHTML = '<label for="city">City</label>' +
                    '<select name="city" id="city" class="form-control" disabled>' +
                    '<option value="">Select City</option></select>';
            } else {
                // All other countries: Switch to Text Inputs
                stateContainer.innerHTML = '<label for="state">State / Province</label>' +
                    '<input type="text" name="state" id="state" class="form-control" placeholder="Type state...">';
                
                cityContainer.innerHTML = '<label for="city">City</label>' +
                    '<input type="text" name="city" id="city" class="form-control" placeholder="Type city...">';
            }
        }

        function updateCities() {
            const country = document.getElementById('country').value;
            const stateSelect = document.getElementById('state');
            const cityContainer = document.getElementById('city-container');
            
            if (country !== "Mexico" || !stateSelect || stateSelect.tagName !== 'SELECT') return;

            const state = stateSelect.value;
            
            if (locationData["Mexico"][state]) {
                cityContainer.innerHTML = '<label for="city">City</label>' +
                    '<select name="city" id="city" class="form-control"></select>';
                const citySelect = document.getElementById('city');
                locationData["Mexico"][state].forEach(city => {
                    const opt = document.createElement('option');
                    opt.value = city;
                    opt.textContent = city;
                    citySelect.appendChild(opt);
                });
            } else {
                cityContainer.innerHTML = '<label for="city">City</label>' +
                    '<input type="text" name="city" id="city" class="form-control" placeholder="Type city...">';
            }
        }
        </script>

            <div style="margin-top: 32px; display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Connect Site</button>
                <a href="/web-manager/public/index.php?route=sites" class="btn btn-outline" style="flex: 1;">Cancel</a>
            </div>
        </form>
    </div>

    <div class="card" style="background: #f0fdf4; border: 1px solid #bcf0da;">
        <h3 style="font-size: 14px; color: #166534; margin-bottom: 8px;">How to connect?</h3>
        <ol style="font-size: 13px; color: #166534; padding-left: 20px;">
            <li>Install the <strong>web-manager Connector</strong> plugin on your WordPress.</li>
            <li>Go to the "web-manager" menu in your WordPress Admin.</li>
            <li>Copy the <strong>Site URL</strong> and <strong>API Key</strong>.</li>
            <li>Paste them here and click Connect.</li>
        </ol>
    </div>
</div>
