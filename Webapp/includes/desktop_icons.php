<style>
  /* Elevate IE window above desktop icons */
  .ie-window { position: relative; z-index: 10; }
  /* Desktop icon tray — fixed top-left of viewport */
  .desktop-tray { position: fixed; top: 16px; left: 16px; display: flex; flex-direction: column; gap: 18px; z-index: 1; pointer-events: none; }
  .desktop-icon { display: flex; flex-direction: column; align-items: center; width: 64px; pointer-events: all; cursor: default; user-select: none; text-decoration: none; }
  .desktop-icon .icon-glyph { font-size: 32px; line-height: 1; filter: drop-shadow(1px 1px 1px rgba(0,0,0,0.6)); }
  .desktop-icon .icon-label { margin-top: 3px; color: #fff; font-family: "MS Sans Serif", "Microsoft Sans Serif", Tahoma, sans-serif; font-size: 10px; text-align: center; line-height: 1.3; padding: 1px 3px; text-shadow: 1px 1px 0 #000, -1px 1px 0 #000, 1px -1px 0 #000, -1px -1px 0 #000; }
  .desktop-icon:hover .icon-label { background: #000080; text-shadow: none; outline: 1px dotted rgba(255,255,255,0.6); }
</style>
<div class="desktop-tray">
  <div class="desktop-icon">
    <span class="icon-glyph">🖥️</span>
    <span class="icon-label">My Computer</span>
  </div>
  <div class="desktop-icon">
    <span class="icon-glyph">🗑️</span>
    <span class="icon-label">Recycle Bin</span>
  </div>
  <a class="desktop-icon" href="profile.php">
    <span class="icon-glyph">🗄️</span>
    <span class="icon-label">Research DB</span>
  </a>
</div>
