<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Home </title>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #0e0f11;
            margin: 0;
            padding: 25px;
            color: #e7e7e7;
        }

        h1 {
            text-align: center;
            font-size: 36px;
            margin-bottom: 25px;
            font-weight: 700;
            color: #f2f2f2;
            text-shadow: 0 0 8px #00baff80;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 22px;
            padding: 10px;
        }

        .device {
            background: #1a1c1f;
            border-radius: 18px;
            padding: 28px 18px;
            text-align: center;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.6),
                        inset 0 0 12px #00000033;
            transition: 0.25s ease;
            cursor: pointer;
            border: 1px solid #2a2d31;
        }

        .device:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 20px #00baff33, 0 0 12px #00baff55 inset;
        }

        .device-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .device-name {
            font-weight: 600;
            font-size: 20px;
            margin-bottom: 8px;
            color: #e8e8e8;
        }

        .status {
            font-size: 16px;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .status.on {
            color: #00eaff;
            text-shadow: 0 0 6px #00eaff;
        }

        .status.off {
            color: #ff3b3b;
            text-shadow: 0 0 6px #ff3b3b;
        }

        .toggle-btn {
            padding: 10px 18px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            font-size: 14px;
            width: 100%;
            transition: 0.2s ease;
        }

        .toggle-on {
            background: #00baff;
            color: black;
            box-shadow: 0 0 10px #00baffAA;
        }

        .toggle-off {
            background: #ff3b3b;
            color: white;
            box-shadow: 0 0 10px #ff3b3bAA;
        }

        .toggle-btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<h1>Smart Home Cenah</h1>

<div class="grid">

    <div class="device" onclick="toggle('lamp')">
        <div class="device-icon">💡</div>
        <div class="device-name">Lamp</div>
        <div id="lamp-status" class="status">Loading...</div>
        <button class="toggle-btn" id="lamp-btn">Loading</button>
    </div>

    <div class="device" onclick="toggle('fan')">
        <div class="device-icon">🌀</div>
        <div class="device-name">Fan</div>
        <div id="fan-status" class="status">Loading...</div>
        <button class="toggle-btn" id="fan-btn">Loading</button>
    </div>

    <div class="device" onclick="toggle('door')">
        <div class="device-icon">🚪</div>
        <div class="device-name">Door</div>
        <div id="door-status" class="status">Loading...</div>
        <button class="toggle-btn" id="door-btn">Loading</button>
    </div>

    <div class="device" onclick="toggle('window')">
        <div class="device-icon">🪟</div>
        <div class="device-name">Window</div>
        <div id="window-status" class="status">Loading...</div>
        <button class="toggle-btn" id="window-btn">Loading</button>
    </div>

    <div class="device" onclick="toggle('pc')">
        <div class="device-icon">💻</div>
        <div class="device-name">PC</div>
        <div id="pc-status" class="status">Loading...</div>
        <button class="toggle-btn" id="pc-btn">Loading</button>
    </div>

    <div class="device" onclick="toggle('tv')">
        <div class="device-icon">📺</div>
        <div class="device-name">TV</div>
        <div id="tv-status" class="status">Loading...</div>
        <button class="toggle-btn" id="tv-btn">Loading</button>
    </div>

</div>

<script>
function loadDevices() {
    fetch("/api/devices")
        .then(res => res.json())
        .then(data => {
            Object.keys(data).forEach(device => {
                const state = data[device];

                document.getElementById(device + "-status").innerHTML =
                    state ? "ON" : "OFF";

                document.getElementById(device + "-status").className =
                    "status " + (state ? "on" : "off");

                document.getElementById(device + "-btn").innerHTML =
                    state ? "Turn Off" : "Turn On";

                document.getElementById(device + "-btn").className =
                    "toggle-btn " + (state ? "toggle-off" : "toggle-on");
            });
        });
}

function toggle(device) {
    fetch("/api/toggle", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        body: JSON.stringify({ name: device })
    })
    .then(() => loadDevices());
}

setInterval(loadDevices, 1000);
loadDevices();
</script>

</body>
</html>
