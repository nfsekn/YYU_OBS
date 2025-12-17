<?php
/**
 * Veritabanı Yönetim Sistemi - Ana Sayfa
 * Ekip üyeleri buradan tüm işlemleri yapabilirler
 */

include 'config/database.php';

$page = $_GET['page'] ?? 'home';
$success_msg = '';
$error_msg = '';

// Veritabanındaki tabloları al
$tables = [];
$result = $conn->query("SHOW TABLES");
if ($result) {
    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBS - Veritabanı Yönetimi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 OBS Ekip Veritabanı Yönetim Sistemi</h1>
            <p>Tüm ekip üyeleri veritabanını görebilir, veri ekleyebilir ve tabloları yönetebilir.</p>
            
            <div class="nav-menu">
                <a href="?page=home" class="nav-btn">🏠 Anasayfa</a>
                <a href="?page=view_tables" class="nav-btn">📋 Tabloları Görüntüle</a>
                <a href="?page=add_data" class="nav-btn">➕ Veri Ekle</a>
                <a href="?page=create_table" class="nav-btn">🆕 Tablo Oluştur</a>
            </div>
        </div>
        
        <div class="content">
            <?php if ($page === 'home'): ?>
                <h2>👋 Hoş Geldiniz!</h2>
                <p style="margin: 15px 0; color: #666; line-height: 1.6;">
                    Bu panel, ekip üyelerinin veritabanına erişmesi, veri eklemesi ve yönetmesi için tasarlanmıştır.
                    Hosting şifresine ihtiyaç yoktur - buradan tüm işlemleri yapabilirsiniz.
                </p>
                
                <h3 style="margin-top: 30px; margin-bottom: 15px;">📊 Veritabanı İstatistikleri</h3>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                    <p><strong>Toplam Tablo Sayısı:</strong> <?php echo count($tables); ?></p>
                    <p><strong>Tablolar:</strong></p>
                    <ul style="margin-left: 20px; margin-top: 10px;">
                        <?php foreach ($tables as $table): ?>
                            <li><?php echo htmlspecialchars($table); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <h3 style="margin-top: 30px; margin-bottom: 15px;">🔧 Ne Yapabilirsiniz?</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px;">
                        <h4>📋 Tabloları Görüntüle</h4>
                        <p style="font-size: 0.95em;">Tüm tabloları ve verileri görebilirsiniz.</p>
                    </div>
                    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 8px;">
                        <h4>➕ Veri Ekle</h4>
                        <p style="font-size: 0.95em;">Tablolara yeni veri ekleyebilirsiniz.</p>
                    </div>
                    <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 20px; border-radius: 8px;">
                        <h4>🆕 Tablo Oluştur</h4>
                        <p style="font-size: 0.95em;">Yeni tablalar oluşturabilirsiniz.</p>
                    </div>
                    <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 20px; border-radius: 8px;">
                        <h4>✏️ Veri Düzenle</h4>
                        <p style="font-size: 0.95em;">Mevcut verileri düzenleyebilirsiniz.</p>
                    </div>
                    <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; padding: 20px; border-radius: 8px;">
                        <h4>🗑️ Veri Sil</h4>
                        <p style="font-size: 0.95em;">İstenmeyen verileri silebilirsiniz.</p>
                    </div>
                </div>
                
            <?php elseif ($page === 'view_tables'): ?>
                <?php include 'view_tables.php'; ?>
                
            <?php elseif ($page === 'add_data'): ?>
                <?php include 'add_data.php'; ?>
                
            <?php elseif ($page === 'create_table'): ?>
                <?php include 'create_table.php'; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
