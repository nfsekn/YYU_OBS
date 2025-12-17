<?php
/**
 * Veritabanı Bağlantı Ayarları
 * Hosting: ftpupload.net
 */

// Veritabanı bilgileri
define('DB_HOST', 'sql204.iceiy.com');
define('DB_PORT', '3306');
define('DB_USER', 'icei_40706000');
define('DB_PASS', 'BenBirAyyasim8');
define('DB_NAME', 'icei_40706000_ogrenci_bilgi_sistemi');

// Veritabanı bağlantısı oluştur
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

// Bağlantı kontrolü
if ($conn->connect_error) {
    die("❌ Veritabanı bağlantı hatası: " . $conn->connect_error);
}

// Karakter setini ayarla
$conn->set_charset("utf8mb4");

// Hata raporlama (production'da kapatılmalı)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
