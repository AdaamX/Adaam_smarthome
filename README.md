# Smart Home Simulation

This project implements a simple smart home system where Laravel acts as the web dashboard controlling 6 devices (Lamp, Fan, Door, Window, PC, TV), represented by LEDs on an Arduino board.

## Features

- Laravel web dashboard with toggle buttons for 6 devices
- API endpoints for device status and toggling
- Arduino ESP8266/ESP32 code to control LEDs based on device states
- Real-time synchronization between web interface and physical LEDs

## Laravel Requirements

### Database Setup

1. Configure your database in `.env` file:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=smarthome
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

2. Run migrations:

   ```
   php artisan migrate
   ```

3. Seed the devices:
   ```
   php artisan db:seed --class=DeviceSeeder
   ```

### Running Laravel

1. Install dependencies:

   ```
   composer install
   npm install
   npm run build
   ```

2. Start the server:

   ```
   php artisan serve
   ```

3. Access the dashboard at `http://localhost:8000/dashboard`

## Arduino Requirements

### Hardware

- ESP8266 or ESP32 board
- 6 LEDs
- 6 x 220Ω resistors
- Jumper wires

### Wiring

Connect the LEDs as follows:

- Lamp LED: GPIO 2 → 220Ω resistor → GND
- Fan LED: GPIO 4 → 220Ω resistor → GND
- Door LED: GPIO 5 → 220Ω resistor → GND
- Window LED: GPIO 18 → 220Ω resistor → GND
- PC LED: GPIO 19 → 220Ω resistor → GND
- TV LED: GPIO 23 → 220Ω resistor → GND

### Software Setup

1. Install Arduino IDE
2. Install ESP8266/ESP32 board support
3. Install required libraries:

   - ESP8266WiFi (for ESP8266) or WiFi (for ESP32)
   - ESP8266HTTPClient (for ESP8266) or HTTPClient (for ESP32)
   - ArduinoJson

4. Open `arduino.ino` in Arduino IDE
5. Update WiFi credentials:
   ```cpp
   const char* ssid = "YOUR_WIFI_SSID";
   const char* password = "YOUR_WIFI_PASSWORD";
   ```
6. Update API URL:
   ```cpp
   const char* apiUrl = "http://YOUR_LARAVEL_IP:8000/api/devices";
   ```
7. Upload the code to your ESP board

## API Endpoints

- `GET /api/devices`: Returns JSON with device states

  ```json
  {
    "lamp": 1,
    "fan": 0,
    "door": 1,
    "window": 0,
    "pc": 1,
    "tv": 0
  }
  ```

- `POST /api/devices/toggle`: Toggles a device state
  - Body: `{"name": "lamp"}`
  - Returns updated device states

## Testing

1. Start Laravel server
2. Access dashboard and toggle devices
3. Check Arduino LEDs respond accordingly
4. Verify API returns correct states

## Troubleshooting

- **WiFi connection fails**: Check SSID/password, ensure ESP is in range
- **HTTP request fails**: Verify Laravel server IP/port, check firewall
- **LEDs not responding**: Check wiring, GPIO pins, power supply
- **Database errors**: Run migrations, check .env configuration
- **CSRF token errors**: Ensure meta tag is present in dashboard view

## Project Structure

- `app/Models/Device.php`: Device model
- `app/Http/Controllers/DeviceController.php`: API controller
- `database/migrations/XXXX_create_devices_table.php`: Devices table migration
- `database/seeders/DeviceSeeder.php`: Device seeder
- `routes/api.php`: API routes
- `routes/web.php`: Web routes
- `resources/views/dashboard.blade.php`: Dashboard view
- `arduino.ino`: Arduino code
