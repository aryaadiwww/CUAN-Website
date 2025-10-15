<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'guru') {
  header("Location: ../index.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Guru - CUAN</title>
  <link rel="stylesheet" href="../style.css"/>
  <link rel="stylesheet" href="../responsive.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    * {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      box-sizing: border-box;
    }
    body {
      background-color: #335165ff;
      min-height: 100vh;
      display: flex;
      flex-direction: row;
      width: 100vw;
      overflow-x: hidden;
    }

    
    @keyframes chartFadeIn {
      0% { opacity: 0; transform: scale(0.95) translateY(40px); }
      100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modern-chart-container canvas {
      background: #335165;
      border-radius: 18px;
      box-shadow: 0 2px 12px #b8355633;
      width: 100% !important;
      max-width: 1100px;
      height: 300px !important;
      max-height: 340px;
      display: block;
      margin: 0 auto;
      transition: box-shadow 0.3s;
      animation: chartPop 1.2s cubic-bezier(.68,-0.55,.27,1.55);
    }
    @keyframes chartPop {
      0% { opacity: 0; transform: scale(0.8); }
      60% { opacity: 1; transform: scale(1.05); }
      100% { opacity: 1; transform: scale(1); }
    }
    @media screen and (max-width: 1100px) {
      .modern-chart-container {
        max-width: 98vw;
        padding: 1.2rem 0.5rem 1.2rem 0.5rem;
      }
      .modern-chart-container canvas {
        max-width: 98vw;
        height: 250px !important;
        max-height: 280px;
      }
    }
    @media screen and (max-width: 600px) {
      .modern-chart-container {
        max-width: 99vw;
        padding: 0.7rem 0.2rem 0.7rem 0.2rem;
      }
      .modern-chart-container canvas {
        max-width: 99vw;
        height: 200px !important;
        max-height: 220px;
      }
    }
    /* Remove old dashboard-chart, chart-title, and info-card styles */
    .sidebar {
      width: 70px;
      background: linear-gradient(135deg, #1a2933ff 0%, #405d6fff  100%);
      color: white;
      transition: width 0.4s cubic-bezier(.68,-0.55,.27,1.55);
      overflow: hidden;
      border-top-right-radius: 0;
      border-bottom-right-radius: 0;
      box-shadow: none;
      position: fixed;
      left: 0;
      top: 0;
      height: 100vh;
      z-index: 1000;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .sidebar.open {
      width: 180px;
      box-shadow: none;
    }
    .sidebar .logo-section {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem 0;
      height: 80px;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.4s cubic-bezier(.68,-0.55,.27,1.55), visibility 0.4s;
    }
    .sidebar.open .logo-section {
      opacity: 1;
      visibility: visible;
      transition-delay: 0.1s;
    }
    .sidebar .logo-section img {
      width: 120px;
      height: 60px;
      margin-right: 10px;
      transition: width 0.4s, height 0.4s, filter 0.4s;
    }
    .sidebar ul {
      list-style: none;
      padding: 0;
      margin-top: 10px;
    }
    .sidebar ul li {
      display: flex;
      align-items: center;
      padding: 14px 18px;
      cursor: pointer;
      border-radius: 18px;
      margin: 8px 8px;
      background: linear-gradient(135deg, #4d7b99ff 0%, #335165ff 100%);
      box-shadow: 0 2px 8px 0 rgba(255,179,71,0.04);
      transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
      position: relative;
      overflow: hidden;
    }
    .sidebar ul li:hover {
      background:  #e4aa95ff;
      transform: scale(1.06) translateX(4px) rotate(-2deg);
      box-shadow: 0 4px 16px 0 rgba(255,94,98,0.12);
    }
    .sidebar ul li .menu-icon {
      font-size: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: margin-right 0.4s cubic-bezier(.68,-0.55,.27,1.55), font-size 0.3s, padding-left 0.3s;

    }
    .sidebar.open ul li .menu-icon {
      margin-right: 15px;
      font-size: 28px;
      padding-left: 0;
    }
    .sidebar span.menu-text {
      display: none;
      font-size: 0.9rem;
      letter-spacing: 0.5px;
      transition: opacity 0.3s, margin-left 0.3s;
      opacity: 0;
      margin-left: 0;
      color: #ffff;
    }
    .sidebar.open span.menu-text {
      display: inline;
      opacity: 1;
      margin-left: 1px;
      color: #ffffffff;
      font-weight: 600;
    }
    .main-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      background: linear-gradient(135deg, #395c74ff 0%, #77aacdff  100%);
      min-height: 100vh;
      margin-left: 70px;
      transition: background 0.4s, margin-left 0.4s;
      width: 100vw;
      overflow-x: hidden;
      overflow-y: auto;
    }
    .sidebar.open ~ .main-content {
      margin-left: 180px;
      transition: margin-left 0.4s cubic-bezier(.68,-0.55,.27,1.55);
    }
    header {
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.5rem 2rem;
      border-bottom-left-radius: 0;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
      transition: background 0.4s;
      background: linear-gradient(135deg, #1a2933ff 0%, #405d6fff  100%);
      height: 80px;
    }
    .hamburger-logo {
      display: flex;
      align-items: center;
      transition: margin-left 0.3s;
    }
    .hamburger {
      font-size: 2.1rem;
      cursor: pointer;
      background: #fff6;
      border: none;
      color: #335165ff;
      margin-right: 1rem;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 8px #ffb34733;
      transition: background 0.3s, color 0.3s, box-shadow 0.3s;
      /* Remove default font-size for svg */
      font-size: unset;
      padding: 0;
    }
    .hamburger svg {
      width: 28px;
      height: 28px;
      display: block;
      transition: transform 0.3s;
    }
    .hamburger:hover {
      background: #fff;
      color: #335165ff;
      box-shadow: 0 4px 16px #ffb34755;
      transform: scale(1.1) rotate(-8deg);
    }
    .profile-menu {
      position: relative;
      display: flex;
      align-items: center;
    }
    .profile-button {
      background: linear-gradient(90deg, #578aacff 0%, #5587a9ff 100%);
      border: none;
      color: #fff;
      cursor: pointer;
      font-weight: bold;
      border-radius: 50px;
      padding: 5px 16px 5px 10px;
      display: flex;
      align-items: center;
      box-shadow: 0 2px 8px #b8355633;
      font-size: 1rem;
      transition: background 0.3s, color 0.3s, box-shadow 0.3s, transform 0.2s;
      position: relative;
    }
    .profile-button:hover {
      background: linear-gradient(90deg, #335165ff 0%, #335165ff 100%);
      color: #fff;
      box-shadow: 0 4px 16px #b8355655;
      transform: scale(1.06) rotate(-2deg);
    }
    .profile-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #fff;
      border: 2px solid #335165ff;
      object-fit: cover;
      box-shadow: 0 2px 8px #b8355633;
      margin-right: 4px;
    }
    .dropdown {
      display: none;
      opacity: 0;
      pointer-events: none;
      position: absolute;
      right: 0;
      top: 110%;
      min-width: 160px;
      background: linear-gradient(135deg, #fff 60%, #DC97A5 100%);
      color: #B83556;
      box-shadow: 0 8px 24px 0 #b8355633;
      margin-top: 8px;
      border-radius: 14px;
      overflow: hidden;
      z-index: 20;
      border: 1.5px solid #DC97A5;
      transform: translateY(-10px) scale(0.98);
      transition: opacity 0.25s, transform 0.25s;
    }
    .dropdown.open {
      display: block;
      opacity: 1;
      pointer-events: auto;
      transform: none;
      animation: dropdownFade 0.3s cubic-bezier(.68,-0.55,.27,1.55);
    }
    @keyframes dropdownFade {
      0% { opacity: 0; transform: translateY(-10px) scale(0.98); }
      100% { opacity: 1; transform: none; }
    }
    .dropdown a {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 12px 18px;
      text-decoration: none;
      color: #B83556;
      font-weight: 600;
      font-size: 1rem;
      border-bottom: 1px solid #f3e6e6;
      transition: background 0.2s, color 0.2s;
    }
    .main-content {
      flex: 1;
      margin-left: 70px;
      transition: margin-left 0.4s cubic-bezier(.68,-0.55,.27,1.55);
      overflow-x: hidden;
    }
    .sidebar.open ~ .main-content {
      margin-left: 180px;
    }

    .dropdown a:hover {
      background: #DC97A5;
      color: #fff;
    }
    .main {
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: center;
      padding: 2.5rem 2.5rem 2.5rem 2.5rem;
      animation: fadeInMain 0.7s cubic-bezier(.68,-0.55,.27,1.55);
    }
    .main-illustration {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 360px;
      min-width: 360px;
      max-width: 460px;
      margin-right: 0.5rem;
      padding: 1.2rem 1.2rem 1.2rem 1.2rem;
      animation: bounceIn 1.2s cubic-bezier(.68,-0.55,.27,1.55);
    }
    .main-illustration img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      border-radius: 12px;
      box-shadow: none;
    }
    .main-content-text {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: center;
      min-width: 260px;
      max-width: 650px;
      background: rgba(255,255,255,0.10);
      border-radius: 16px;
      padding: 2rem 2.2rem 2rem 2.2rem;
      box-shadow: 0 2px 12px #b8355633;
    }
    .main-content-text h1 {
      font-size: 2.2rem;
      color: #fff;
      margin-bottom: 0.7rem;
      font-weight: 700;
      letter-spacing: 1px;
      text-shadow: 0 2px 8px #b8355633;
      word-break: break-word;
    }
    .main-content-text p {
      font-size: 1.15rem;
      font-style: italic;
      color: #fff;
      font-weight: 500;
      background: linear-gradient(90deg, #dc97a586 0%, #dc97a586 100%);
      padding: 0.9rem 1.4rem;
      border-radius: 10px;
      box-shadow: 0 2px 8px #b8355633;
      margin: 0;
      text-shadow: 0 1px 4px #b8355633;
      line-height: 1.6;
    }
    @keyframes bounceIn {
      0% { opacity: 0; transform: scale(0.7) translateY(60px); }
      60% { opacity: 1; transform: scale(1.1) translateY(-10px); }
      100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    @keyframes fadeInMain {
      0% { opacity: 0; transform: translateY(30px) scale(0.98); }
      100% { opacity: 1; transform: none; }
    }
    @media screen and (max-width: 900px) {
      .sidebar {
        width: 54px;
        border-radius: 0 30px 30px 0;
      }
      .sidebar.open {
        width: 120px;
      }
      .main-content {
        margin-left: 54px;
        transition: margin-left 0.4s cubic-bezier(.68,-0.55,.27,1.55);
      }
      .sidebar.open ~ .main-content {
        margin-left: 120px;
      }
      header {
        padding: 1rem 1rem 1rem 1.5rem;
        height: 70px;
      }
    }
    @media screen and (max-width: 600px) {
      .main {
        flex-direction: column;
        gap: 1.2rem;
        padding: 1.2rem 0.7rem 1rem 0.7rem;
        align-items: stretch;
      }
      .main-illustration {
        margin: 0 auto 1rem auto;
        padding: 0.7rem;
        max-width: 180px;
        min-width: 120px;
        height: 160px;
      }
      .main-content-text {
        padding: 1.2rem 1rem;
        min-width: unset;
        max-width: unset;
      }
      .sidebar.open {
        width: 120px;
      }
      .sidebar .logo-section img {
        width: 70px;
        height: 36px;
      }
      .main-content {
        margin-left: 54px;
        transition: margin-left 0.4s cubic-bezier(.68,-0.55,.27,1.55);
      }
      .sidebar.open ~ .main-content {
        margin-left: 120px;
      }
      header {
        height: 60px;
      }
    }
    .dashboard-chart {
      padding: 1.5rem 0.5rem 0.5rem 0.5rem; /* tambah padding atas */
      position: relative;
      overflow: visible; /* biar label keluar card tetap terlihat */
    }
    
    /* Styling untuk judul chart */
    .chart-title {
      color: #fff;
      font-size: 1.4rem;
      font-weight: 600;
      text-align: center;
      margin-bottom: 1rem;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
      animation: fadeIn 0.8s ease-out;
    }
    
    /* Styling untuk card informasi */
    .dashboard-info-cards {
      display: flex;
      flex-direction: column;
      gap: 1.2rem;
      padding: 0.5rem 2rem 0.5rem 2rem; /* padding atas diperkecil */
      width: 100%;
      margin-bottom: 2rem;
      margin-top: 2rem;
    }
    
    .info-card-row {
      display: flex;
      gap: 2rem;
      width: 100%;
    }
    
    .info-card {
      flex: 1;
      border-radius: 16px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      padding: 1.5rem;
      display: flex;
      align-items: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      animation: fadeIn 0.6s ease-out;
      position: relative;
      overflow: hidden;
    }
    
    /* Rainbow gradient untuk setiap info card agar lebih gelap/transparan */
    .info-card-row:nth-child(1) .info-card:nth-child(1) {
      background: linear-gradient(135deg, rgba(255,88,88,0.40) 0%, rgba(255,179,71,0.40) 100%); /* merah ke orange gelap/transparan */
    }
    .info-card-row:nth-child(1) .info-card:nth-child(2) {
      background: linear-gradient(135deg, rgba(255,179,71,0.40) 0%, rgba(247,255,88,0.40) 100%); /* orange ke kuning gelap/transparan */
    }
    .info-card-row:nth-child(2) .info-card:nth-child(1) {
      background: linear-gradient(135deg, rgba(127,255,88,0.40) 0%, rgba(88,255,247,0.40) 100%); /* hijau ke cyan gelap/transparan */
    }
    .info-card-row:nth-child(2) .info-card:nth-child(2) {
      background: linear-gradient(135deg, rgba(88,166,255,0.40) 0%, rgba(184,88,255,0.40) 100%); /* biru ke ungu gelap/transparan */
    }
    
    .info-card::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
      opacity: 0;
      transition: opacity 0.5s ease;
      pointer-events: none;
    }
    
    .info-card:hover {
      transform: translateY(-5px) rotate(-1deg);
      box-shadow: 0 12px 36px rgba(0, 0, 0, 0.15);
    }
    
    /* Animasi berbeda untuk setiap card saat hover */
    .info-card:nth-child(1):hover {
      transform: translateY(-5px) rotate(-1deg);
    }
    
    .info-card:nth-child(2):hover {
      transform: translateY(-5px) rotate(1deg);
    }
    
    .info-card-row:nth-child(2) .info-card:nth-child(1):hover {
      transform: translateY(-5px) rotate(1deg);
    }
    
    .info-card-row:nth-child(2) .info-card:nth-child(2):hover {
      transform: translateY(-5px) rotate(-1deg);
    }
    
    .info-card:hover::before {
      opacity: 1;
    }
    
    .info-card-icon {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1.5rem;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
      transition: transform 0.3s ease, background 0.3s ease;
    }
    
    /* Warna berbeda untuk setiap icon */
    .info-card:nth-child(1) .info-card-icon {
      background: linear-gradient(135deg, #e4aa95ff 0%, #DC97A5 100%);
    }
    
    .info-card:nth-child(2) .info-card-icon {
      background: linear-gradient(135deg, #f7c48d 0%, #f5b16d 100%);
    }
    
    .info-card-row:nth-child(2) .info-card:nth-child(1) .info-card-icon {
      background: linear-gradient(135deg, #a8d8a8 0%, #86c486 100%);
    }
    
    .info-card-row:nth-child(2) .info-card:nth-child(2) .info-card-icon {
      background: linear-gradient(135deg, #a5c1e5 0%, #7da7db 100%);
    }
    
    .info-card:hover .info-card-icon {
      transform: scale(1.1) rotate(-5deg);
      animation: pulse 1.5s infinite;
    }
    
    /* Animasi berbeda untuk setiap icon saat hover */
    .info-card:nth-child(1):hover .info-card-icon {
      transform: scale(1.1) rotate(-5deg);
      animation: pulse 1.5s infinite;
    }
    
    .info-card:nth-child(2):hover .info-card-icon {
      transform: scale(1.1) rotate(5deg);
      animation: pulse 1.5s infinite 0.2s;
    }
    
    .info-card-row:nth-child(2) .info-card:nth-child(1):hover .info-card-icon {
      transform: scale(1.1) rotate(5deg);
      animation: pulse 1.5s infinite 0.4s;
    }
    
    .info-card-row:nth-child(2) .info-card:nth-child(2):hover .info-card-icon {
      transform: scale(1.1) rotate(-5deg);
      animation: pulse 1.5s infinite 0.6s;
    }
    
    @keyframes pulse {
      0% { transform: scale(1.1); }
      50% { transform: scale(1.2); }
      100% { transform: scale(1.1); }
    }
    
    .info-card-icon img {
      width: 40px;
      height: 40px;
      object-fit: contain;
      filter: brightness(1.1);
    }
    
    .info-card-content {
      flex: 1;
    }
    
    .info-card-title {
      color: #fff;
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }
    
    .info-card-value {
      color: #fff;
      font-size: 2.2rem;
      font-weight: 700;
      margin-bottom: 0.3rem;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
      position: relative;
      display: inline-block;
      padding: 0 0.3rem;
      transition: transform 0.3s ease, text-shadow 0.3s ease;
    }
    
    .info-card:hover .info-card-value {
      transform: scale(1.1);
      text-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
    
    /* Warna berbeda untuk setiap nilai */
    .info-card:nth-child(1) .info-card-value {
      background: linear-gradient(90deg, #ffffff 0%, #f5f5f5 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .info-card:nth-child(2) .info-card-value {
      background: linear-gradient(90deg, #ffffff 0%, #f8f8f8 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .info-card-row:nth-child(2) .info-card:nth-child(1) .info-card-value {
      background: linear-gradient(90deg, #ffffff 0%, #f5f5f5 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .info-card-row:nth-child(2) .info-card:nth-child(2) .info-card-value {
      background: linear-gradient(90deg, #ffffff 0%, #f8f8f8 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .info-card-desc {
      color: rgba(255, 255, 255, 0.8);
      font-size: 0.9rem;
      font-style: italic;
    }
    .dashboard-row-cards {
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: stretch;
      gap: 2rem;
      margin-bottom: 2rem;
    }
    .dashboard-card {
      flex: 1 1 0;
      min-width: 0;
      background: linear-gradient(135deg, #b8355683 60%, #DC97A5 100%);
      /* box-shadow removed to eliminate pink shadow */
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 1.2rem 0.7rem 1.2rem 0.7rem;
      transition: box-shadow 0.3s, transform 0.2s;
      position: relative;
      overflow: hidden;
      cursor: pointer;
      max-width: 260px;
      min-height: 160px;
      transition: box-shadow 0.3s, transform 0.2s;
    }
    .statistik-icon {
      width: 64px;
      height: 64px;
      background: linear-gradient(135deg, #DC97A5 60%, #B83556 100%);
      border-radius: 50%;
      padding: 8px;
      box-shadow: 0 2px 12px #dc97a555;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.2s, box-shadow 0.2s;
      animation: statistikIconBounce 1.2s cubic-bezier(.68,-0.55,.27,1.55);
    }
    .statistik-icon img {
      width: 40px;
      height: 40px;
      object-fit: contain;
      display: block;
      filter: drop-shadow(0 2px 8px #fff6);
    }
    .dashboard-card:hover .statistik-icon {
      transform: scale(1.15) rotate(-8deg);
      box-shadow: 0 6px 24px #b8355655;
    }
    .statistik-label {
      color: #fff;
      font-size: 1.1rem;
      font-weight: 600;
      letter-spacing: 0.5px;
      margin-bottom: 0.3rem;
      text-shadow: 0 2px 8px #b8355633;
      text-align: center;
    }
    .statistik-value {
      color: #fff;
      font-size: 2.1rem;
      font-weight: 700;
      letter-spacing: 1px;
      margin-bottom: 0.2rem;
      text-shadow: 0 2px 12px #dc97a555;
      text-align: center;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(90deg, #DC97A5 0%, #B83556 100%);
      border-radius: 10px;
      padding: 0.3rem 1.2rem;
      box-shadow: 0 2px 8px #b8355633;
      display: inline-block;
    }
    .dashboard-row-charts {
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: stretch;
      gap: 2rem;
      margin-bottom: 0.5rem;
    }
    .dashboard-chart {
      background: #ffffff50;
      border-radius: 16px;
      /* box-shadow removed to eliminate pink shadow */
      padding: 1.2rem 0.5rem 1.2rem 0.5rem;
      position: relative;
      overflow: visible;
      display: flex;
      align-items: center;
      justify-content: center;
      min-width: 0;
      cursor: pointer;
      opacity: 1;
      transform: scale(1);
      transition: none;
      width: 400px;
      min-height: 240px;
      max-width: 100%;
      margin-bottom: 2rem;
    }
    .dashboard-chart canvas {
      background: #f5f7fa;
      border-radius: 12px;
      box-shadow: 0 2px 8px #b8355633;
      width: 320px !important;
      height: 160px !important;
      max-width: 100%;
      max-height: 100%;
      display: block;
    }
    @media screen and (max-width: 900px) {
      .dashboard-row-cards {
        flex-direction: column;
        gap: 1.2rem;
        align-items: center;
      }
      .dashboard-card {
        max-width: 98vw;
        width: 98vw;
      }
      .dashboard-row-charts {
        flex-direction: column;
        gap: 1.2rem;
        align-items: center;
      }
      .dashboard-chart {
        width: 98vw;
        max-width: 98vw;
        min-height: 220px;
        padding: 1rem 0.5rem;
      }
      .dashboard-chart canvas {
        width: 320px !important;
        height: 120px !important;
        max-width: 98vw;
        max-height: 120px;
      }
      
      /* Responsive info cards */
      .info-card-row {
        flex-direction: column;
        gap: 1rem;
      }
      
      .info-card {
        padding: 1.2rem;
      }
      
      .info-card-icon {
        width: 60px;
        height: 60px;
        margin-right: 1rem;
      }
      
      .info-card-value {
        font-size: 1.8rem;
      }
    }
    @media screen and (max-width: 600px) {
      .dashboard-row-cards {
        flex-direction: column;
        gap: 1rem;
      }
      .dashboard-card {
        max-width: 98vw;
        width: 98vw;
      }
      .dashboard-row-charts {
        flex-direction: column;
        gap: 1rem;
      }
      .dashboard-chart {
        width: 98vw;
        max-width: 98vw;
        min-height: 140px;
        padding: 0.5rem 0.2rem;
      }
      .dashboard-chart canvas {
        width: 220px !important;
        height: 80px !important;
        max-width: 98vw;
        max-height: 80px;
      }
      
      /* Responsive info cards for very small screens */
      .dashboard-info-cards {
        padding: 0.5rem 1rem;
      }
      
      .info-card {
        padding: 1rem;
      }
      
      .info-card-icon {
        width: 50px;
        height: 50px;
        margin-right: 0.8rem;
      }
      
      .info-card-icon img {
        width: 30px;
        height: 30px;
      }
      
      .info-card-title {
        font-size: 0.9rem;
      }
      
      .info-card-value {
        font-size: 1.6rem;
      }
      
      .info-card-desc {
        font-size: 0.8rem;
      }
    }
  </style>
</head>
<body>
  <div class="sidebar" id="sidebar">
    <div class="logo-section">
      <img src="../img/cuan.png" alt="Logo CUAN">
    </div>
    <ul>
      <li onclick="location.href='guru_dashboard.php'">
        <span class="menu-icon"><img src="../img/home.png" alt="Beranda" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Beranda</span>
      </li>
      <li onclick="location.href='guru_jadwal.php'">
        <span class="menu-icon"><img src="../img/calendar.png" alt="Jadwal" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Jadwal</span>
      </li>
      <li onclick="location.href='guru_datasiswa.php'">
        <span class="menu-icon"><img src="../img/siswa.png" alt="Data Siswa" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Data Siswa</span>
      </li>
      <li onclick="location.href='guru_orangtuasiswa.php'">
        <span class="menu-icon"><img src="../img/ortu.png" alt="Orang Tua Siswa" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Orang Tua Siswa</span>
      </li>
      <li onclick="location.href='guru_aktivitasterbaru.php'">
        <span class="menu-icon"><img src="../img/aktivitas.png" alt="Orang Tua Siswa" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Aktivitas Terbaru</span>
      </li>
      <li onclick="location.href='guru_siswaberprestasi.php'">
        <span class="menu-icon"><img src="../img/piala.png" alt="Siswa Berprestasi" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Siswa Berprestasi</span>
      </li>
    </ul>
  </div>

  <div class="main-content">
    <header>
      <div class="hamburger-logo">
        <button class="hamburger" id="sidebarToggleBtn" onclick="toggleSidebar()">
          <!-- Panah kanan default, akan diganti JS -->
          <svg id="sidebarArrow" viewBox="0 0 24 24" fill="none"><path d="M8 5l8 7-8 7" stroke="#335165ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </div>
      <div class="profile-menu">
        <button class="profile-button" onclick="toggleDropdown()">
          <img src="../img/profile.png" alt="Avatar" class="profile-avatar" />
          <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left:2px;"><path d="M5 8L10 13L15 8" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="dropdown" id="dropdown">
          <a href="guru_edit_profile.php"><svg width="18" height="18" fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19.5 3 21l1.5-4L16.5 3.5z"/></svg>Edit Profil</a>
          <a href="../logout.php"><svg width="18" height="18" fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>Logout</a>
        </div>
      </div>
    </header>

    <div class="main">
    </div>
    <!-- Diagram Nilai Rata-rata Siswa (Aesthetic, Modern, Playful) -->
    <div class="modern-chart-container">
      <canvas id="avgScoreChart"></canvas>
    </div>

  <!-- Restore the 4 info cards below the diagram -->
  <div class="dashboard-info-cards">
    <div class="info-card-row">
      <div class="info-card" onclick="location.href='kehadiran.php'" style="cursor:pointer;">
        <div class="info-card-icon">
          <img src="../img/kehadiran.png" alt="Kehadiran">
        </div>
        <div class="info-card-content">
          <h3 class="info-card-title">Kehadiran</h3>
          <p class="info-card-value" id="kehadiranPersen">...</p>
          <p class="info-card-desc">Presentase kehadiran siswa</p>
        </div>
      </div>
      <div class="info-card" onclick="location.href='nilai.php'" style="cursor:pointer;">
        <div class="info-card-icon">
          <img src="../img/nilai.png" alt="Nilai">
        </div>
        <div class="info-card-content">
          <h3 class="info-card-title">Nilai</h3>
          <p class="info-card-value">88</p>
          <p class="info-card-desc">Rata-rata nilai siswa</p>
        </div>
      </div>
    </div>
    <div class="info-card-row">
      <div class="info-card" onclick="location.href='matapelajaran.php'" style="cursor:pointer;">
        <div class="info-card-icon">
          <img src="../img/tugas.png" alt="Tugas">
        </div>
        <div class="info-card-content">
          <h3 class="info-card-title">Mata Pelajaran</h3>
          <p class="info-card-value">6</p>
          <p class="info-card-desc">Mata pelajaran siswa</p>
        </div>
      </div>
      <div class="info-card" onclick="location.href='ekstrakulikuler.php'" style="cursor:pointer;">
        <div class="info-card-icon">
          <img src="../img/seni.png" alt="Ekstrakurikuler">
        </div>
        <div class="info-card-content">
          <h3 class="info-card-title">Ekstrakurikuler</h3>
          <p class="info-card-value">90%</p>
          <p class="info-card-desc">Partisipasi siswa</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Remove duplicate/unused chart container styles -->

  <!-- Chart.js CDN dan Plugin -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
  
  <!-- Register Chart.js plugins only in external JS -->

  <!-- Dropdown menu functionality -->
  <script>
    function toggleDropdown() {
      const dropdown = document.getElementById("dropdown");
      dropdown.classList.toggle("open");
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
      const dropdown = document.getElementById("dropdown");
      const profileBtn = document.querySelector('.profile-button');
      if (dropdown && profileBtn && !profileBtn.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove("open");
      }
    });
  </script>

  <!-- Chart logic moved to guru_dashboard_chart.js -->
  
  <script src="../music-player.js"></script>
  <script src="../guru_dashboard_chart.js?v=<?php echo time(); ?>"></script>
</body>
</html>