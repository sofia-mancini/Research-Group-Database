<style>
  .ie-window { position: relative; z-index: 10; }
  .desktop-icons { position: fixed; top: 10px; left: 10px; display: flex; flex-direction: column; gap: 8px; z-index: 1; }
  .desktop-icon { display: flex; flex-direction: column; align-items: center; gap: 2px; width: 64px; cursor: pointer; padding: 2px; text-decoration: none; color: inherit; }
  .desktop-icon:hover .icon-label { background: #000080; color: #fff; }
  .icon-img { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 22px; }
  .icon-label { font-size: 10px; color: #fff; text-align: center; text-shadow: 1px 1px 1px #000; padding: 1px 2px; line-height: 1.2; }
</style>
<div class="desktop-icons">
  <div class="desktop-icon">
    <div class="icon-img">🖥️</div>
    <div class="icon-label">My Computer</div>
  </div>
  <div class="desktop-icon">
    <div class="icon-img">🗑️</div>
    <div class="icon-label">Recycle Bin</div>
  </div>
  <div class="desktop-icon">
    <div class="icon-img">🔬</div>
    <div class="icon-label">Research DB</div>
  </div>
</div>
