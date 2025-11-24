<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Smart Home — Dashboard</title>

<style>
  :root{
    --bg-1:#071226;
    --bg-2:#0e1220;
    --glass: rgba(255,255,255,0.03);
    --glass-2: rgba(255,255,255,0.02);
    --accent:#6b52ff; /* purple accent like figma */
    --glass-border: rgba(255,255,255,0.05);
    --card-radius:18px;
    --soft: rgba(255,255,255,0.03);
  }

  html,body{
    height:100%;
    margin:0;
    font-family: "Segoe UI", Roboto, system-ui, -apple-system, "Helvetica Neue", Arial;
    -webkit-font-smoothing:antialiased;
    -moz-osx-font-smoothing:grayscale;
    background:
      radial-gradient( circle at 10% 10%, rgba(107,82,255,0.06), transparent 10%),
      radial-gradient( circle at 90% 80%, rgba(107,52,255,0.04), transparent 12%),
      linear-gradient(180deg,var(--bg-1),var(--bg-2) 60%);
    color:#e7e7e7;
    padding:36px 48px;
    box-sizing:border-box;
  }

  h1{
    text-align:center;
    margin:0;
    font-size:32px;
    font-weight:700;
    letter-spacing:0.2px;
    text-shadow:0 6px 18px rgba(0,0,0,0.6);
  }
  .sub {
    text-align:center;
    color:rgba(231,231,231,0.6);
    margin-top:8px;
    margin-bottom:30px;
    font-size:15px;
  }

  .grid {
    display:grid;
    grid-template-columns: repeat(3, 1fr);
    gap:24px;
    max-width:1180px;
    margin: 0 auto 34px auto;
  }

  /* card */
  .device {
    position:relative;
    padding:28px 26px;
    border-radius:var(--card-radius);
    background: linear-gradient(180deg, rgba(255,255,255,0.012), rgba(0,0,0,0.12));
    border: 1px solid var(--glass-border);
    box-shadow: 0 12px 30px rgba(3,6,12,0.6), inset 0 1px 0 rgba(255,255,255,0.01);
    backdrop-filter: blur(8px) saturate(1.05);
    transition: transform 260ms cubic-bezier(.2,.9,.3,1), box-shadow 260ms, border-color 260ms;
    overflow:hidden;
    min-height:160px;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:space-between;
    cursor: pointer;
  }

  .device:hover{
    transform: translateY(-12px);
    border-color: rgba(107,82,255,0.16);
    box-shadow:
      0 28px 60px rgba(98,72,200,0.12),
      0 10px 28px rgba(0,0,0,0.6),
      inset 0 0 22px rgba(255,255,255,0.016);
    background: linear-gradient(135deg, rgba(255,255,255,0.02), rgba(12,10,18,0.25));
  }

  /* icon square */
  .icon-wrap{
    width:64px;
    height:64px;
    border-radius:14px;
    background: rgba(255,255,255,0.02);
    display:flex;
    align-items:center;
    justify-content:center;
    margin-bottom:12px;
    transition: transform 200ms, box-shadow 200ms, background 200ms;
  }

  .device:hover .icon-wrap {
    transform: translateY(-4px);
    box-shadow: 0 10px 24px rgba(107,82,255,0.06);
    background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(107,82,255,0.02));
  }

  .icon{
    font-size:26px;
    opacity:0.95;
  }

  .title {
    font-size:18px;
    font-weight:600;
    margin:0;
    margin-bottom:6px;
    letter-spacing:0.2px;
  }

  .state {
    color:rgba(231,231,231,0.64);
    font-size:13px;
    margin-bottom:12px;
  }

  .state.on { color: #9be7ff; text-shadow:0 0 6px rgba(0,210,255,0.12); }
  .state.off { color: rgba(231,231,231,0.46); }

  /* custom toggle switch */
  .switch {
    --w:56px; --h:28px; --pad:4px;
    width:var(--w);
    height:var(--h);
    background: linear-gradient(180deg, rgba(255,255,255,0.04), rgba(0,0,0,0.08));
    border-radius:999px;
    position:relative;
    border:1px solid rgba(255,255,255,0.04);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.02);
    display:inline-block;
    transition: background 200ms, box-shadow 200ms, transform 200ms;
  }

  .knob {
    width: calc(var(--h) - var(--pad) * 2);
    height: calc(var(--h) - var(--pad) * 2);
    background: white;
    border-radius:50%;
    position:absolute;
    top:var(--pad);
    left:var(--pad);
    transition:left 260ms cubic-bezier(.2,.9,.3,1), transform 160ms;
    box-shadow: 0 6px 18px rgba(0,0,0,0.5);
  }

  .switch.on {
    background: linear-gradient(90deg, rgba(0,200,255,0.14), rgba(107,82,255,0.14));
    box-shadow: 0 10px 30px rgba(107,82,255,0.08), inset 0 0 10px rgba(255,255,255,0.02);
  }
  .switch.on .knob {
    left: calc(var(--w) - var(--h) + var(--pad));
    transform: scale(0.98);
  }

  /* ripple */
  .ripple {
    position:absolute;
    border-radius:50%;
    background: rgba(107,82,255,0.12);
    transform: scale(0);
    animation: ripple-ani 640ms ease-out forwards;
    pointer-events:none;
    mix-blend-mode: screen;
  }
  @keyframes ripple-ani { to { transform: scale(1); opacity:0; } }

  /* bottom add device button */
  .actions {
    display:flex;
    justify-content:center;
    margin-top:18px;
  }

</style>
</head>
<body>

<h1>Smart Home Control</h1>
<div class="sub">Manage all your smart devices in one place</div>

<div class="grid">

  <div class="device" onclick="doClick(event,'lamp')">
    <div class="icon-wrap"><div class="icon">💡</div></div>
    <div class="title">Lamp</div>
    <div id="lamp-state" class="state off">Inactive</div>
    <div class="switch" id="lamp-switch" aria-pressed="false" onclick="event.stopPropagation(); toggle('lamp', this)">
      <div class="knob"></div>
    </div>
  </div>

  <div class="device" onclick="doClick(event,'fan')">
    <div class="icon-wrap"><div class="icon">🌀</div></div>
    <div class="title">Fan</div>
    <div id="fan-state" class="state off">Inactive</div>
    <div class="switch" id="fan-switch" onclick="event.stopPropagation(); toggle('fan', this)"><div class="knob"></div></div>
  </div>

  <div class="device" onclick="doClick(event,'door')">
    <div class="icon-wrap"><div class="icon">🚪</div></div>
    <div class="title">Door</div>
    <div id="door-state" class="state off">Inactive</div>
    <div class="switch" id="door-switch" onclick="event.stopPropagation(); toggle('door', this)"><div class="knob"></div></div>
  </div>

  <div class="device" onclick="doClick(event,'window')">
    <div class="icon-wrap"><div class="icon">🪟</div></div>
    <div class="title">Window</div>
    <div id="window-state" class="state off">Inactive</div>
    <div class="switch" id="window-switch" onclick="event.stopPropagation(); toggle('window', this)"><div class="knob"></div></div>
  </div>

  <div class="device" onclick="doClick(event,'pc')">
    <div class="icon-wrap"><div class="icon">💻</div></div>
    <div class="title">PC</div>
    <div id="pc-state" class="state off">Inactive</div>
    <div class="switch" id="pc-switch" onclick="event.stopPropagation(); toggle('pc', this)"><div class="knob"></div></div>
  </div>

  <div class="device" onclick="doClick(event,'tv')">
    <div class="icon-wrap"><div class="icon">📺</div></div>
    <div class="title">TV</div>
    <div id="tv-state" class="state off">Inactive</div>
    <div class="switch" id="tv-switch" onclick="event.stopPropagation(); toggle('tv', this)"><div class="knob"></div></div>
  </div>

</div>

<script>
/* ripple on card click */
function doClick(e, device){
  const card = e.currentTarget;
  ripple(card, e);
  // also toggle when clicking card body (optional): comment out if not wanted
  // toggle(device, document.getElementById(device + '-switch'));
}

/* create ripple element */
function ripple(card, e){
  const rect = card.getBoundingClientRect();
  const r = document.createElement('span');
  r.className = 'ripple';
  const size = Math.max(rect.width, rect.height) * 1.1;
  r.style.width = r.style.height = size + 'px';
  r.style.left = (e.clientX - rect.left - size/2) + 'px';
  r.style.top = (e.clientY - rect.top - size/2) + 'px';
  card.appendChild(r);
  setTimeout(()=> r.remove(), 700);
}

/* load states from API and update UI */
function loadDevices() {
  fetch('/api/devices')
    .then(r => r.json())
    .then(data => {
      ['lamp','fan','door','window','pc','tv'].forEach(name=>{
        const state = !!data[name];
        const stateEl = document.getElementById(name + '-state');
        const sw = document.getElementById(name + '-switch');

        stateEl.textContent = state ? 'Active' : 'Inactive';
        stateEl.className = 'state ' + (state ? 'on' : 'off');

        if(state){
          sw.classList.add('on');
          sw.setAttribute('aria-pressed','true');
        } else {
          sw.classList.remove('on');
          sw.setAttribute('aria-pressed','false');
        }
      });
    })
    .catch(err => {
      console.error('Failed to fetch /api/devices', err);
    });
}

/* toggle call: accepts device name and switch element (optional) */
function toggle(name, switchEl){
  if(!switchEl) return;
  const intendedState = !switchEl.classList.contains('on');

  // update UI optimistically
  if(intendedState){
    switchEl.classList.add('on');
  } else {
    switchEl.classList.remove('on');
  }

  fetch('/api/toggle', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify({ name, state: intendedState })
  })
  .then(res => res.json())
  .then(() => loadDevices())
  .catch(err => {
    console.error('toggle failed', err);
    // rollback UI on failure
    if(intendedState){
      switchEl.classList.remove('on');
    } else {
      switchEl.classList.add('on');
    }
  });
}

/* initial load + keep in sync */
loadDevices();
setInterval(loadDevices, 1200);

</script>
</body>
</html>
