<?php
require_once 'includes/session.php';
require_login($logged_in);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Access Denied — Research Group DB</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: "MS Sans Serif", "Microsoft Sans Serif", Tahoma, sans-serif;
      font-size: 11px;
      background-color: #008080;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .window {
      width: 380px;
      background: #c0c0c0;
      border-top: 2px solid #fff;
      border-left: 2px solid #fff;
      border-right: 2px solid #404040;
      border-bottom: 2px solid #404040;
      box-shadow: 2px 2px 0 #000;
    }
    .title-bar {
      background: linear-gradient(90deg, #000080, #1084d0);
      padding: 3px 4px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .title-bar-text { color: #fff; font-weight: bold; font-size: 11px; }
    .title-bar-controls { display: flex; gap: 2px; }
    .win-btn {
      width: 16px; height: 14px;
      background: #c0c0c0;
      border-top: 1px solid #fff; border-left: 1px solid #fff;
      border-right: 1px solid #808080; border-bottom: 1px solid #808080;
      font-size: 9px; display: flex; align-items: center;
      justify-content: center; cursor: pointer;
      font-weight: bold; color: #000; text-decoration: none;
    }
    .window-body { padding: 16px; }
    .error-row {
      display: flex;
      align-items: flex-start;
      gap: 14px;
      margin-bottom: 16px;
    }
    .error-icon {
      width: 40px; height: 40px; flex-shrink: 0;
      border: 2px solid #000; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 22px; font-weight: bold; color: #ff0000;
      font-family: "Times New Roman", serif;
      background: #c0c0c0;
    }
    .error-text { font-size: 11px; line-height: 1.6; }
    .error-text strong { display: block; margin-bottom: 4px; font-size: 12px; }
    .divider { border: none; border-top: 1px solid #808080; border-bottom: 1px solid #fff; margin: 10px 0; }
    .btn-row { display: flex; justify-content: center; gap: 8px; }
    .win98-btn {
      min-width: 75px; height: 23px;
      background: #c0c0c0;
      border-top: 2px solid #fff; border-left: 2px solid #fff;
      border-right: 2px solid #808080; border-bottom: 2px solid #808080;
      font-family: inherit; font-size: 11px; cursor: pointer;
      text-decoration: none; color: #000;
      display: flex; align-items: center; justify-content: center;
    }
    .win98-btn:active {
      border-top: 2px solid #808080; border-left: 2px solid #808080;
      border-right: 2px solid #fff; border-bottom: 2px solid #fff;
    }
  </style>
</head>
<body>
  <div class="window">
    <div class="title-bar">
      <div class="title-bar-text">Access Denied</div>
      <div class="title-bar-controls">
        <div class="win-btn">_</div>
        <div class="win-btn">□</div>
        <a href="profile.php" class="win-btn">✕</a>
      </div>
    </div>
    <div class="window-body">
      <div class="error-row">
        <div class="error-icon">!</div>
        <div class="error-text">
          <strong>Access Denied</strong>
          You do not have permission to perform this action.<br><br>
          This operation requires a higher access level.
          Please contact a <strong>Principal Investigator</strong>
          if you believe you need access.
        </div>
      </div>
      <hr class="divider">
      <div class="btn-row">
        <a href="javascript:history.back()" class="win98-btn">« Back</a>
        <a href="profile.php" class="win98-btn">🏠 Home</a>
      </div>
    </div>
  </div>
</body>
</html>