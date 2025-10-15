<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['level'] != 'siswa') {
  header("Location: ../index.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Siswa - CUAN</title>
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
    /* Portofolio Card Styles */
    .portofolio-section {
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 2.5rem;
      margin-top: 0.5rem;
      animation: fadeInMain 1.1s cubic-bezier(.68,-0.55,.27,1.55);
    }
    .portofolio-card {
      background: linear-gradient(120deg, #633579ff 0%, #59388aff 60%, #421d5aff 100%);
      /* Playful purple gradient */
      box-shadow: 0 6px 32px #7f53c055, 0 2px 12px #a259c655;
      border-radius: 28px;
      padding: 2.2rem 2.5rem 2.2rem 2.5rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
      max-width: 1040px;
      min-width: 0;
      margin: 0 auto;
      position: relative;
      overflow: hidden;
      transition: box-shadow 0.3s, transform 0.2s;
      animation: cardFadeIn 1.2s cubic-bezier(.68,-0.55,.27,1.55);
      opacity: 0;
      animation-fill-mode: forwards;
    }
    .portofolio-card:hover {
      box-shadow: 0 12px 48px #b83556aa, 0 6px 24px #dc97a5aa;
      transform: scale(1.04) rotate(-2deg);
    }
    .portofolio-title {
      font-size: 2rem;
      font-weight: 700;
      color: #B83556;
      margin-bottom: 1.2rem;
      letter-spacing: 1px;
      text-shadow: 0 2px 8px #dc97a555;
      background: linear-gradient(90deg, #B83556 0%, #DC97A5 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .portofolio-content {
      display: flex;
      flex-direction: row;
      align-items: center;
      gap: 2.5rem;
      width: 100%;
      justify-content: flex-start;
    }
    .portofolio-photo {
      width: 170px;
      height: 170px;
      border-radius: 50%;
      overflow: hidden;
      box-shadow: 0 4px 16px #dc97a555;
      border: 3px solid #ffffffff;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: bounceIn 1.2s cubic-bezier(.68,-0.55,.27,1.55);
      flex-shrink: 0;
    }
    .portofolio-photo img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 50%;
      transition: transform 0.3s;
    }
    .portofolio-photo img:hover {
      transform: scale(1.08) rotate(-6deg);
    }
    .upload-foto-form {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      margin-top: 8px;
      gap: 4px;
    }
    .upload-foto-form input[type="file"] {
      font-size: 0.6rem;
      padding: 1.5px 0;
      width: 120px;
      border: none;
      background: none;
    }
    .upload-foto-form button {
      font-size: 0.6rem;
      padding: 1px 8px;
      border-radius: 8px;
      border: none;
      background: #421d5aff;
      color: #fff;
      cursor: pointer;
      margin-top: 2px;
      transition: background 0.2s;
    }
    .upload-foto-form button:hover {
      background: #DC97A5;
    }
    .portofolio-info {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: center;
      gap: 0.5rem;
      width: 100%;
    }
    .portofolio-title {
      font-size: 2.5rem;
      font-weight: 700;
      color: #fff;
      background: linear-gradient(90deg, #fff 0%, #e0c3fc 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 0.2rem;
      letter-spacing: 0.5px;
      text-shadow: 0 2px 8px #7f53c055;
      text-align: center;
      width: 100%;
    }
    .portofolio-desc {
      font-size: 1.05rem;
      font-style: italic;
      color: #f3e6ff;
      font-style: italic;
      margin-bottom: 0.7rem;
      width: 100%;
      text-align: left;
      line-height: 1.5;
    }
    .portofolio-name {
      font-size: 1.5rem;
      font-weight: 600;
      color: #fff;
      background: linear-gradient(90deg, #fff 0%, #e0c3fc 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 0.2rem;
      letter-spacing: 0.5px;
      text-shadow: 0 2px 8px #7f53c055;
      text-align: left;
      width: 100%;
    }
    .portofolio-class {
      font-size: 1rem;
      font-weight: 500;
      color: #fff;
      background: linear-gradient(90deg, #a259c6 0%, #7f53c0 100%);
      padding: 0.4rem 1.2rem;
      border-radius: 12px;
      box-shadow: 0 2px 8px #b8355633;
      text-shadow: 0 1px 4px #b8355633;
      letter-spacing: 0.5px;
      text-align: left;
      width: fit-content;
    }
    @media screen and (max-width: 900px) {
      .portofolio-card {
        max-width: 98vw;
        width: 98vw;
        padding: 1.2rem 0.7rem 1.2rem 0.7rem;
      }
      .portofolio-content {
        flex-direction: column;
        gap: 1.2rem;
        align-items: center;
      }
      .portofolio-photo {
        width: 70px;
        height: 70px;
      }
      .portofolio-title {
        font-size: 1.1rem;
      }
      .portofolio-desc {
        font-size: 0.95rem;
      }
      .portofolio-name {
        font-size: 1rem;
      }
      .portofolio-class {
        font-size: 0.95rem;
        padding: 0.3rem 0.7rem;
      }
    }
    @media screen and (max-width: 600px) {
      .portofolio-card {
        padding: 1.2rem 0.7rem 1.2rem 0.7rem;
        min-width: unset;
        max-width: 98vw;
      }
      .portofolio-content {
        flex-direction: column;
        gap: 1rem;
        align-items: center;
      }
      .portofolio-photo {
        width: 60px;
        height: 60px;
      }
      .portofolio-title {
        font-size: 1rem;
      }
      .portofolio-desc {
        font-size: 0.85rem;
      }
      .portofolio-name {
        font-size: 0.9rem;
      }
      .portofolio-class {
        font-size: 0.8rem;
        padding: 0.3rem 0.7rem;
      }
    }
    body {
      background: linear-gradient(120deg, #B83556 0%, #DC97A5 60%, #fff0f5 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: row;
      width: 100vw;
      overflow-x: hidden;
      transition: background 0.7s cubic-bezier(.68,-0.55,.27,1.55);
    }
    .sidebar {
      width: 70px;
      background: linear-gradient(135deg, #1a2933ff 0%, #405d6fff  100%);
      color: white;
      transition: width 0.4s cubic-bezier(.68,-0.55,.27,1.55);
      overflow: hidden;
      border-top-right-radius: 0;
      border-bottom-right-radius: 0;
      box-shadow: 0 4px 24px #1a293355;
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
      background: linear-gradient(135deg, #634338ff 0%, #ffb296ff 100%);
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
      font-size: 1rem;
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
      background: linear-gradient(135deg, #B83556 0%, #DC97A5 100%);
      min-height: 100vh;
      margin-left: 70px;
      transition: background 0.4s, margin-left 0.4s;
      width: 100vw;
      overflow-x: hidden;
      overflow-y: auto;
    }
    .sidebar.open ~ .main-content {
      margin-left: 180px;
      transition: margin-left 0.4s;
    }
    header {
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.5rem 2rem;
      border-bottom-left-radius: 0;
      box-shadow: bottom 2px 8px rgba(0, 0, 0, 1);
      transition: background 0.4s;
      background-color: #a82747ff;
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
      color: #B83556;
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
      color: #ff5e62;
      box-shadow: 0 4px 16px #ffb34755;
      transform: scale(1.1) rotate(-8deg);
    }
    .profile-menu {
      position: relative;
      display: flex;
      align-items: center;
    }
    .profile-button {
      background: linear-gradient(90deg, #dc97a5b2 0%, #dc97a5b2 100%);
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
      background: linear-gradient(90deg, #DC97A5 0%, #DC97A5 100%);
      color: #fff;
      box-shadow: 0 4px 16px #b8355655;
      transform: scale(1.06) rotate(-2deg);
    }
    .profile-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #fff;
      border: 2px solid #B83556;
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
    .dropdown a:last-child {
      border-bottom: none;
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
        transition: margin-left 0.4s;
      }
      .sidebar.open ~ .main-content {
        margin-left: 120px;
      }
      header {
        padding: 1rem 1rem 1rem 1.5rem;
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
      }
      .sidebar.open ~ .main-content {
        margin-left: 120px;
      }
      .main {
        padding: 1.2rem 0.7rem 1rem 0.7rem;
      }
    }
    .dashboard-chart {
      padding: 1.5rem 0.5rem 0.5rem 0.5rem; /* tambah padding atas */
      position: relative;
      overflow: visible; /* biar label keluar card tetap terlihat */
      width: 600px;
      min-width: 600px;
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
      box-shadow: 0 4px 24px #b8355633, 0 1.5px 8px #dc97a533;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 1.2rem 0.7rem 1.2rem 0.7rem;
      transition: box-shadow 0.3s, transform 0.2s;
      position: relative;
      overflow: hidden;
      cursor: pointer;
      max-width: 320px;
      min-height: 100px;
      animation: cardFadeIn 0.9s cubic-bezier(.68,-0.55,.27,1.55);
      opacity: 0;
      animation-fill-mode: forwards;
      border-radius: 20px;
    }
    .dashboard-card:nth-child(1) { animation-delay: 0.1s; }
    .dashboard-card:nth-child(2) { animation-delay: 0.25s; }
    .dashboard-card:nth-child(3) { animation-delay: 0.4s; }
    @keyframes cardFadeIn {
      0% { opacity: 0; transform: scale(0.8) translateY(40px); }
      100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .dashboard-card:hover {
      box-shadow: 0 10px 40px #b83556aa, 0 4px 16px #dc97a5aa;
      transform: scale(1.08) rotate(-2deg);
      z-index: 2;
    }
    .dashboard-card .statistik-badge {
      position: absolute;
      top: 12px;
      right: 18px;
      background: #fff;
      color: #B83556;
      font-size: 0.85rem;
      font-weight: bold;
      border-radius: 8px;
      padding: 2px 10px;
      box-shadow: 0 2px 8px #b8355633;
      letter-spacing: 0.5px;
      opacity: 0.92;
      display: flex;
      align-items: center;
      gap: 4px;
      animation: cardFadeIn 1.2s cubic-bezier(.68,-0.55,.27,1.55);
      animation-delay: 0.7s;
      animation-fill-mode: forwards;
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
      font-size: 1.5rem;
      font-weight: 800;
      letter-spacing: 0.5px;
      margin-bottom: 0.3rem;
      text-shadow: 0 2px 8px #b8355633;
      text-align: center;
    }
    .statistik-value {
      color: #fff;
      font-size: 1.2rem;
      letter-spacing: 1px;
      margin-bottom: 0.2rem;
      text-shadow: 0 2px 12px #dc97a555;
      text-align: center;
      font-family: 'Poppins', sans-serif;
      /* background will be set per card using new classes */
    /* Statistik value backgrounds to match card gradients */
    .statistik-value-kehadiran {
      background: linear-gradient(135deg, rgba(186, 53, 82, 0.95) 0%, rgba(255,99,132,0.95) 100%) !important;
    }
    .statistik-value-tugas {
      background: linear-gradient(135deg, rgba(186, 145, 49, 0.95) 0%, rgba(255,205,86,0.95) 100%) !important;
      color: #fff;
    }
    .statistik-value-nilai {
      background: linear-gradient(135deg, rgba(29, 108, 161, 0.95) 0%, rgba(54,162,235,0.95) 100%) !important;
      color: #fff;
    }
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
      box-shadow: 0 2px 12px #b8355633, 0 1.5px 8px #dc97a533;
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
      width: 500px;
      min-width: 500px;
      margin-bottom: 2rem;
    }
    .dashboard-chart canvas {
      background: #f5f7fa;
      border-radius: 12px;
      box-shadow: 0 2px 8px #b8355633;
      width: 480px !important;
      height: 180px !important;
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
        width: 580px !important;
        height: 200px !important;
        max-width: 98vw;
        max-height: 200px;
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
    }
    .card-playful-1 {
      background: linear-gradient(135deg, rgba(186, 53, 82, 0.95) 0%, rgba(255,99,132,0.95) 100%) !important;
      box-shadow: 0 4px 24px rgba(186, 53, 82, 0.95);
    }
    .card-playful-2 {
      background: linear-gradient(135deg, rgba(186, 145, 49, 0.95) 0%, rgba(255,205,86,0.95) 100%) !important;
      box-shadow: 0 4px 24px rgba(186, 145, 49, 0.95);
    }
    .card-playful-3 {
      background: linear-gradient(135deg, rgba(29, 108, 161, 0.95) 0%, rgba(54,162,235,0.95) 100%) !important;
      box-shadow: 0 4px 24px rgba(29, 108, 161, 0.95);
    }
  </style>
</head>
<body>
  <div class="sidebar" id="sidebar">
    <div class="logo-section">
      <img src="../img/cuan.png" alt="Logo CUAN">
    </div>
    <ul>
      <li onclick="location.href='siswa_dashboard.php'">
        <span class="menu-icon"><img src="../img/home.png" alt="Beranda" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Beranda</span>
      </li>
      <li onclick="location.href='siswa_matapelajaran.php'">
        <span class="menu-icon"><img src="../img/book.png" alt="Mata Pelajaran" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Mata Pelajaran</span>
      </li>
      <li onclick="location.href='siswa_jadwal.php'">
        <span class="menu-icon"><img src="../img/calendar.png" alt="Jadwal" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Jadwal</span>
      </li>
      <li onclick="location.href='siswa_games.php'">
        <span class="menu-icon"><img src="../img/games.png" alt="Games" style="width:18px;height:18px;object-fit:contain;"></span>
        <span class="menu-text">Games</span>
      </li>
    </ul>
  </div>

  <div class="main-content">
    <header>
      <div class="hamburger-logo">
        <button class="hamburger" id="sidebarToggleBtn" onclick="toggleSidebar()">
          <!-- Panah kanan default, akan diganti JS -->
          <svg id="sidebarArrow" viewBox="0 0 24 24" fill="none"><path d="M8 5l8 7-8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </div>
      <div class="profile-menu">
        <button class="profile-button" onclick="toggleDropdown()">
          <img src="../img/profile.png" alt="Avatar" class="profile-avatar" />
          <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left:2px;"><path d="M5 8L10 13L15 8" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="dropdown" id="dropdown">
          <a href="siswa_edit_profile.php"><svg width="18" height="18" fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19.5 3 21l1.5-4L16.5 3.5z"/></svg>Edit Profil</a>
          <a href="../logout.php"><svg width="18" height="18" fill="none" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>Logout</a>
        </div>
      </div>
    </header>

    <div class="main">
      <div class="main-illustration">
        <img src="../img/halo.png" alt="Anak SD" />
      </div>
      <div class="main-content-text">
        <h1>Halo, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Siswa'; ?>!</h1>
        <p>"Jangan bandingkan dirimu dengan orang lain, bandingkanlah dirimu dengan dirimu yang kemarin!"</p>
      </div>

    </div>
    <!-- Statistik & Diagram Section (Improved Layout) -->
    <section class="dashboard-statistik-diagram">
      <div class="dashboard-row dashboard-row-cards">
        <div class="dashboard-card statistik-card-kehadiran card-playful-1" onclick="location.href='siswa_kehadiran_detail.php'">
          <span class="statistik-badge"><i class="fa-solid fa-calendar-check"></i> Bulanan</span>
          <div class="statistik-icon"><img src="../img/kehadiran.png" alt="Kehadiran"></div>
          <div class="statistik-label">Kehadiran</div>
          <div class="statistik-value statistik-value-kehadiran" id="cardKehadiranValue">-</div>
        </div>
        <div class="dashboard-card statistik-card-tugas card-playful-2" onclick="location.href='siswa_aktivitas_detail.php'">
          <span class="statistik-badge"><i class="fa-solid fa-clipboard-check"></i> Terbaru</span>
          <div class="statistik-icon"><img src="../img/aktivitas.png" alt="Aktivitas"></div>
          <div class="statistik-label">Aktivitas</div>
          <div class="statistik-value statistik-value-tugas" id="cardAktivitasValue" style="font-size:1rem; line-height:1.2; text-align:left;">-</div>
        </div>
        <div class="dashboard-card statistik-card-nilai card-playful-3" onclick="location.href='siswa_nilai_detail.php'">
          <span class="statistik-badge"><i class="fa-solid fa-star"></i> Rata-rata</span>
          <div class="statistik-icon"><img src="../img/nilai.png" alt="Nilai"></div>
          <div class="statistik-label">Nilai</div>
          <div class="statistik-value statistik-value-nilai" id="cardNilaiValue">-</div>
        </div>
      </div>
      <div class="dashboard-row dashboard-row-charts">
        <div class="dashboard-chart dashboard-chart-bar" style="animation: cardFadeIn 1.1s 0.5s cubic-bezier(.68,-0.55,.27,1.55) forwards; opacity:0;">
          <canvas id="barChart" width="480" height="180"></canvas>
        </div>
        <div class="dashboard-chart dashboard-chart-line" style="animation: cardFadeIn 1.1s 0.7s cubic-bezier(.68,-0.55,.27,1.55) forwards; opacity:0;">
          <canvas id="lineChart" width="480" height="180"></canvas>
        </div>
      </div>
    </section>

    <!-- Portofolio Card Section -->
    <section class="portofolio-section">
      <div class="portofolio-card" style="position:relative;">
        <h2 class="portofolio-title">Portofolio</h2>
        <div class="portofolio-content">
          <div style="display: flex; flex-direction: column; align-items: flex-start;">
            <div class="portofolio-photo">
              <?php
              // Ambil data profil dari API (file JSON per user)
              $foto_url = '../img/profile.png';
              $profile_data = null;
              $username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
              if ($username) {
                $profile_file = __DIR__ . "/profile_data/{$username}.json";
                if (file_exists($profile_file)) {
                  $profile_data = json_decode(file_get_contents($profile_file), true);
                  if (isset($profile_data['foto']) && $profile_data['foto'] && file_exists(__DIR__ . '/../uploads/portofolio/' . $profile_data['foto'])) {
                    $foto_url = '../uploads/portofolio/' . htmlspecialchars($profile_data['foto']);
                  }
                }
              }
              ?>
              <img src="<?php echo $foto_url; ?>" alt="Foto Siswa" />
            </div>
            <form class="upload-foto-form" action="upload_foto.php" method="post" enctype="multipart/form-data">
              <input type="file" name="foto" accept="image/*" required />
              <button type="submit">Unggah Foto</button>
            </form>
          </div>
          <div class="portofolio-info">
            <div class="portofolio-desc">Portofolio adalah kumpulan pencapaian, karya, dan data diri siswa yang menunjukkan perkembangan dan prestasi selama belajar di CUAN.</div>
            <div class="portofolio-name">
              <?php
                $namaLengkap = '';
                $username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
                if ($username) {
                  $profile_file = __DIR__ . "/profile_data/{$username}.json";
                  if (file_exists($profile_file)) {
                    $profile_data = json_decode(file_get_contents($profile_file), true);
                    if (isset($profile_data['nama_lengkap']) && trim($profile_data['nama_lengkap']) !== '') {
                      $namaLengkap = htmlspecialchars($profile_data['nama_lengkap']);
                    }
                  }
                }
                echo $namaLengkap !== '' ? $namaLengkap : (isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Siswa');
              ?>
            </div>
            <div class="portofolio-class">
              <?php echo isset($_SESSION['kelas']) ? htmlspecialchars($_SESSION['kelas']) : 'Kelas 4-A'; ?>
            </div>
          </div>
        </div>
        <a href="detail_portofolio.php" class="btn-detail-portofolio">
          <i class="fa fa-arrow-right"></i> Detail
        </a>
      </div>
    </section>

    <style>
    .btn-detail-portofolio {
      position: absolute;
      right: 18px;
      bottom: 18px;
      background: linear-gradient(90deg, #633579ff  0%, #d16effff  100%);
      color: #fff;
      border: none;
      border-radius: 18px;
      padding: 7px 18px 7px 14px;
      font-size: 1.2rem;
      font-weight: 600;
      box-shadow: 0 2px 8px #b8355633;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 7px;
      z-index: 10;
      transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
    }
    .btn-detail-portofolio:hover {
      background: linear-gradient(90deg, #DC97A5 0%, #B83556 100%);
      color: #fff;
      box-shadow: 0 4px 16px #b8355655;
      transform: scale(1.06) translateY(-2px);
    }
    </style>

  <!-- Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
  <script>
    // Realtime metrics for cards sourced from detail pages' data
    const CURRENT_USER = <?php echo json_encode(isset($_SESSION['username']) ? $_SESSION['username'] : ''); ?>;

    async function loadKehadiranCard() {
      try {
        // Ambil data kehadiran detail dari sumber yang sama dengan halaman siswa_kehadiran_detail.php
        const res = await fetch('../api/kehadiran_data.json?_ts=' + Date.now(), { cache: 'no-store' });
        const data = await res.json();
  // Data format: array of { username, tanggal, status }
  const my = Array.isArray(data) ? data.filter(r => r.username === CURRENT_USER) : [];
  // Total pertemuan: hanya yang sudah diisi status (status !== null/undefined/empty string)
  const pertemuanDiisi = my.filter(r => r.status !== undefined && r.status !== null && String(r.status).trim() !== '').length;
  const hadirCount = my.filter(r => String(r.status) === '1').length;
  const percent = pertemuanDiisi > 0 ? Math.round((hadirCount / pertemuanDiisi) * 100) : 0;
  const el = document.getElementById('cardKehadiranValue');
  if (el) el.textContent = `${hadirCount} Hadir / ${pertemuanDiisi} Pertemuan (${percent}%)`;
      } catch (e) {
        const el = document.getElementById('cardKehadiranValue');
        if (el) el.textContent = '-';
      }
    }

    async function loadAktivitasCard() {
      try {
        const res = await fetch('../api/aktivitas_terbaru.php?_ts=' + Date.now(), { cache: 'no-store' });
        const json = await res.json();
        const list = json && json.status === 'success' && Array.isArray(json.data) ? json.data : [];
        // Hitung bahan ajar (oleh guru)
        const bahanAjar = list.filter(a => a.kategori === 'bahanajar').length;
        // Tugas/Evaluasi siswa (kumpul) untuk user login
        const tugasKumpul = list.filter(a => (a.kategori === 'tugas' || a.kategori === 'evaluasi') && a.username === CURRENT_USER && a.aksi === 'kumpul').length;
        // Tugas/Evaluasi yang sudah dibuat guru (asumsi sebagai total), belum dikumpulkan = total dibuat - dikumpulkan user ini (approx)
        const totalTugasEvalGuru = list.filter(a => (a.kategori === 'tugas' || a.kategori === 'evaluasi') && (a.peran === 'guru' || a.aksi === 'buat')).length;
        const tugasBelum = Math.max(totalTugasEvalGuru - tugasKumpul, 0);
        // Chat masuk: ambil chat grup + direct ke user ini
        // Ambil dari file JSON langsung agar cepat, fallback 0 jika gagal
        let chatMasuk = 0;
        try {
          const chatRes = await fetch('../ipas_chat.json', { cache: 'no-store' });
          if (chatRes.ok) {
            const chats = await chatRes.json();
            if (Array.isArray(chats)) {
              chatMasuk = chats.filter(m => (m.target === 'all') || (m.target === CURRENT_USER && m.from !== CURRENT_USER)).length;
            }
          }
        } catch (_) {}
        const el = document.getElementById('cardAktivitasValue');
        if (el) {
          el.style.whiteSpace = 'pre-line';
          el.innerText = `Bahan ajar: ( ${bahanAjar} )\nBelum dikumpulkan: ( ${tugasBelum} )\nSudah dikumpulkan: ( ${tugasKumpul} )\nChat masuk: ( ${chatMasuk} )`;
        }
      } catch (e) {
        const el = document.getElementById('cardAktivitasValue');
        if (el) el.textContent = '-';
      }
    }

    async function loadNilaiCard() {
      try {
        // Ambil submissions untuk nilai (mirroring halaman guru nilai IPAS)
        const tugasSubRes = await fetch('../tugas_submissions.json', { cache: 'no-store' });
        const evalSubRes = await fetch('../evaluasi_submissions.json', { cache: 'no-store' });
        const tugasSubs = tugasSubRes.ok ? await tugasSubRes.json() : [];
        const evalSubs = evalSubRes.ok ? await evalSubRes.json() : [];
        let arr = [];
        if (Array.isArray(tugasSubs)) {
          tugasSubs.forEach(s => { if (s && s.siswa_id === CURRENT_USER && s.nilai !== undefined && s.nilai !== null && s.nilai !== '') { arr.push(Number(s.nilai)); } });
        }
        if (Array.isArray(evalSubs)) {
          evalSubs.forEach(s => { if (s && s.siswa_id === CURRENT_USER && s.nilai !== undefined && s.nilai !== null && s.nilai !== '') { arr.push(Number(s.nilai)); } });
        }
        const avg = arr.length ? Math.round(arr.reduce((a,b)=>a+b,0) / arr.length) : 0;
        const el = document.getElementById('cardNilaiValue');
        if (el) el.textContent = `${avg}`;
      } catch (e) {
        const el = document.getElementById('cardNilaiValue');
        if (el) el.textContent = '-';
      }
    }

    document.addEventListener('DOMContentLoaded', function(){
      loadKehadiranCard();
      loadAktivitasCard();
      loadNilaiCard();
    });


    // Warna playful
    const playfulColors = [
      'rgba(255, 99, 132, 0.85)',   // pink
      'rgba(54, 162, 235, 0.85)'    // blue
    ];
    const playfulBorderColors = [
      'rgba(255, 99, 132, 1)',
      'rgba(54, 162, 235, 1)'
    ];
    const playfulPointColors = [
      'rgba(255, 99, 132, 1)',
      'rgba(54, 162, 235, 1)'
    ];

    // Ambil data dinamis dari card
    async function getChartValues() {
      // Kehadiran
      let hadirValue = 0;
      let nilaiValue = 0;
      try {
        // Ambil data kehadiran dengan cara yang sama seperti card kehadiran
        const res = await fetch('../api/kehadiran_data.json?_ts=' + Date.now(), { cache: 'no-store' });
        const data = await res.json();
        const my = Array.isArray(data) ? data.filter(r => r.username === CURRENT_USER) : [];
        const pertemuanDiisi = my.filter(r => r.status !== undefined && r.status !== null && String(r.status).trim() !== '').length;
        const hadirCount = my.filter(r => String(r.status) === '1').length;
        hadirValue = pertemuanDiisi > 0 ? Math.round((hadirCount / pertemuanDiisi) * 100) : 0;
        // Nilai
        const tugasSubRes = await fetch('../tugas_submissions.json', { cache: 'no-store' });
        const evalSubRes = await fetch('../evaluasi_submissions.json', { cache: 'no-store' });
        const tugasSubs = tugasSubRes.ok ? await tugasSubRes.json() : [];
        const evalSubs = evalSubRes.ok ? await evalSubRes.json() : [];
        let arr = [];
        if (Array.isArray(tugasSubs)) {
          tugasSubs.forEach(s => { if (s && s.siswa_id === CURRENT_USER && s.nilai !== undefined && s.nilai !== null && s.nilai !== '') { arr.push(Number(s.nilai)); } });
        }
        if (Array.isArray(evalSubs)) {
          evalSubs.forEach(s => { if (s && s.siswa_id === CURRENT_USER && s.nilai !== undefined && s.nilai !== null && s.nilai !== '') { arr.push(Number(s.nilai)); } });
        }
        nilaiValue = arr.length ? Math.round(arr.reduce((a,b)=>a+b,0) / arr.length) : 0;
      } catch (e) {}
      return [hadirValue, nilaiValue];
    }

    async function renderCharts() {
      const chartLabels = ['Kehadiran', 'Nilai'];
      const [hadirValue, nilaiValue] = await getChartValues();
      const chartData = [hadirValue, nilaiValue];

      // Bar Chart
      new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
          labels: chartLabels,
          datasets: [{
            label: 'Skor',
            data: chartData,
            backgroundColor: playfulColors,
            borderColor: playfulBorderColors,
            borderWidth: 2,
            borderRadius: 16,
          }]
        },
        options: {
          plugins: {
            legend: { display: false },
            datalabels: {
              color: '#fff',
              font: { weight: 'bold', size: 14 },
              anchor: 'center', // label di tengah batang
              align: 'center',
              clamp: true,
              padding: {
                top: 0,
                bottom: 0
              },
              formatter: function(value, ctx) {
                // Responsif: jika batang terlalu kecil, label tetap terlihat
                if (ctx.chart.data.labels[ctx.dataIndex] === 'Kehadiran') {
                  return value + '%';
                } else {
                  return value;
                }
              },
              // Responsif: jika batang terlalu kecil, ubah warna label
              display: function(context) {
                // Selalu tampilkan label
                return true;
              },
            }
          },
          scales: {
            x: { ticks: { color: playfulBorderColors, font: { size: 16 } } },
            y: { beginAtZero: true, ticks: { color: playfulBorderColors, font: { size: 16 } } }
          }
        },
        plugins: [ChartDataLabels]
      });

      // Line Chart
      new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
          labels: chartLabels,
          datasets: [{
            label: 'Skor',
            data: chartData,
            fill: false,
            borderColor: 'rgba(255, 99, 132, 1)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.4,
            pointBackgroundColor: playfulPointColors,
            pointBorderColor: '#fff',
            pointRadius: 10,
            pointHoverRadius: 14,
          }]
        },
        options: {
          plugins: {
            legend: { display: false },
            datalabels: {
              color: '#fff',
              backgroundColor: 'rgba(255, 99, 132, 1)',
              font: { weight: 'bold', size: 12 },
              anchor: 'end',
              align: 'bottom',
              offset: 2,
              borderRadius: 8,
              padding: 6,
              formatter: function(value, ctx) {
                if (ctx.chart.data.labels[ctx.dataIndex] === 'Kehadiran') {
                  return value + '%';
                } else {
                  return value;
                }
              },
            }
          },
          scales: {
            x: { ticks: { color: playfulBorderColors, font: { size: 16 } } },
            y: { beginAtZero: true, ticks: { color: playfulBorderColors, font: { size: 16 } } }
          }
        },
        plugins: [ChartDataLabels]
      });
    }

    document.addEventListener('DOMContentLoaded', function(){
      loadKehadiranCard();
      loadAktivitasCard();
      loadNilaiCard();
      renderCharts();
    });

    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('open');
      updateSidebarArrow();
    }
    function updateSidebarArrow() {
      const sidebar = document.getElementById('sidebar');
      const arrow = document.getElementById('sidebarArrow');
      if (sidebar.classList.contains('open')) {
        // Panah kiri (masuk)
        arrow.innerHTML = '<path d="M16 5l-8 7 8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      } else {
        // Panah kanan (keluar)
        arrow.innerHTML = '<path d="M8 5l8 7-8 7" stroke="#B83556" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
      }
    }
    // Inisialisasi panah saat halaman dimuat
    document.addEventListener('DOMContentLoaded', updateSidebarArrow);

    function toggleDropdown() {
      const dropdown = document.getElementById("dropdown");
      dropdown.classList.toggle("open");
    }
    document.addEventListener('click', function(e) {
      const dropdown = document.getElementById("dropdown");
      const profileBtn = document.querySelector('.profile-button');
      if (!profileBtn.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove("open");
      }
    });
  </script>
<script src="../music-player.js"></script>
</body>
</html>