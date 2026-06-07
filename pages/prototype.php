<style>
:root {
  --bg: #eef3f8;
  --card: #ffffff;
  --text: #10213a;
  --muted: #6b7280;
  --border: #dce6f3;
  --primary: #0f766e;
  --footer-bg: #1d4ed8;
  --green: #22c55e;
  --orange: #f59e0b;
  --red: #ef4444;
  --purple: #0f766e;
  --hod-active: #16a34a;
  --owner-active: #ea580c;
  --hr-purple: #115e59;
  --radius: 14px;
  --shadow: 0 10px 30px rgba(21, 42, 78, 0.12);
  --phone-w: 390px;
  --phone-h: 844px;
}
body {
  background: var(--bg);
  color: var(--text);
}
iframe {
  position: relative;
  z-index: 1;
  width: var(--phone-w);
  height: var(--phone-h);
  border: 10px solid var(--primary);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  margin: 0px 20px;
  float: left;
}
.colors {
  float: left;
  margin: 10px;
  border: 1px solid var(--border);
  min-width: 300px;
  background: var(--card);
  font-size: 30px;
  line-height: 75px;
}
.colors .box {
  float: left;
  height: 80px;
  width: 80px;
}
.colors .color {
  float: left;
  color: var(--text);
  font-size: 20px;
  padding-left: 20px;
}
.copy-icon {
  cursor: pointer;
  display: inline-block;
  margin-left: 10px;
  font-size: 18px;
  opacity: 0.8;
}
.copy-icon:hover {
  opacity: 1;
}
</style>
<div class="row" style="margin: 50px;">
<iframe src="<?php echo "prototype/index.html"?>"></iframe>
<div class="colors"><div class="color-box"><div class="box" style="background: var(--primary);"></div><div class="color">#0f766e <span class="copy-icon" data-color="#0f766e" onclick="copyColorCode(this)" title="Copy color code">📋</span></div></div></div>
<div class="colors"><div class="color-box"><div class="box" style="background: var(--footer-bg);"></div><div class="color">#1d4ed8 <span class="copy-icon" data-color="#1d4ed8" onclick="copyColorCode(this)" title="Copy color code">📋</span></div></div></div>
<div class="colors"><div class="color-box"><div class="box" style="background: var(--green);"></div><div class="color">#22c55e <span class="copy-icon" data-color="#22c55e" onclick="copyColorCode(this)" title="Copy color code">📋</span></div></div></div>
<div class="colors"><div class="color-box"><div class="box" style="background: var(--orange);"></div><div class="color">#f59e0b <span class="copy-icon" data-color="#f59e0b" onclick="copyColorCode(this)" title="Copy color code">📋</span></div></div></div>
<div class="colors"><div class="color-box"><div class="box" style="background: var(--red);"></div><div class="color">#ef4444 <span class="copy-icon" data-color="#ef4444" onclick="copyColorCode(this)" title="Copy color code">📋</span></div></div></div>
<div class="colors"><div class="color-box"><div class="box" style="background: var(--hod-active);"></div><div class="color">#16a34a <span class="copy-icon" data-color="#16a34a" onclick="copyColorCode(this)" title="Copy color code">📋</span></div></div></div>
<div class="colors"><div class="color-box"><div class="box" style="background: var(--owner-active);"></div><div class="color">#ea580c <span class="copy-icon" data-color="#ea580c" onclick="copyColorCode(this)" title="Copy color code">📋</span></div></div></div>
<div class="colors"><div class="color-box"><div class="box" style="background: var(--hr-purple);"></div><div class="color">#115e59 <span class="copy-icon" data-color="#115e59" onclick="copyColorCode(this)" title="Copy color code">📋</span></div></div></div>
<script>
function copyColorCode(el) {
  var color = el.getAttribute('data-color');
  if (!color) return;
  navigator.clipboard.writeText(color).then(function() {
    var original = el.textContent;
    el.textContent = '✅';
    setTimeout(function() {
      el.textContent = original;
    }, 1000);
  }).catch(function() {
    alert('Copy failed. Please copy manually: ' + color);
  });
}
</script>