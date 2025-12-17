<?php
/**
 * Tablo Oluştur
 */

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_table') {
    $table_name = $_POST['table_name'];
    $fields_data = $_POST['fields'] ?? [];
    
    if (empty($table_name)) {
        $msg = '<div class="alert alert-error">❌ Tablo adı boş olamaz!</div>';
    } elseif (empty($fields_data)) {
        $msg = '<div class="alert alert-error">❌ En az bir sütun ekleyin!</div>';
    } else {
        // Tablo adını güvenli hale getir
        $table_name = preg_replace('/[^a-zA-Z0-9_]/', '', $table_name);
        
        // CREATE TABLE sorgusu oluştur
        $fields = [];
        $primary_key = null;
        
        foreach ($fields_data as $idx => $field) {
            if (!empty($field['name'])) {
                $name = preg_replace('/[^a-zA-Z0-9_]/', '', $field['name']);
                $type = $field['type'] ?? 'VARCHAR(100)';
                $options = '';
                
                if (!empty($field['primary'])) {
                    $options .= ' PRIMARY KEY AUTO_INCREMENT';
                    $primary_key = $name;
                }
                if (!empty($field['not_null'])) {
                    $options .= ' NOT NULL';
                }
                if (!empty($field['unique'])) {
                    $options .= ' UNIQUE';
                }
                
                $fields[] = "$name $type$options";
            }
        }
        
        if (empty($fields)) {
            $msg = '<div class="alert alert-error">❌ Geçerli bir sütun ekleyin!</div>';
        } else {
            $sql = "CREATE TABLE IF NOT EXISTS $table_name (" . implode(", ", $fields) . ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            if ($conn->query($sql)) {
                $msg = '<div class="alert alert-success">✅ Tablo başarıyla oluşturuldu!</div>';
                // Tabloları yeniden al
                $tables = [];
                $result = $conn->query("SHOW TABLES");
                if ($result) {
                    while ($row = $result->fetch_row()) {
                        $tables[] = $row[0];
                    }
                }
            } else {
                $msg = '<div class="alert alert-error">❌ Hata: ' . $conn->error . '</div>';
            }
        }
    }
}
?>

<h2>🆕 Yeni Tablo Oluştur</h2>

<?php echo $msg; ?>

<form method="POST" style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
    <input type="hidden" name="action" value="create_table">
    
    <div class="form-group">
        <label for="table_name">Tablo Adı:</label>
        <input type="text" id="table_name" name="table_name" placeholder="Örn: ogrenciler, dersler, notlar" required>
    </div>
    
    <h3 style="margin: 30px 0 20px;">📋 Sütunlar</h3>
    
    <div id="fields-container">
        <div class="field-group">
            <h4>Sütun 1</h4>
            <div class="form-group">
                <label>Sütun Adı:</label>
                <input type="text" name="fields[0][name]" placeholder="id, ad, email, vb." required>
            </div>
            <div class="form-group">
                <label>Veri Tipi:</label>
                <select name="fields[0][type]">
                    <option value="INT">INT (Sayı)</option>
                    <option value="VARCHAR(100)" selected>VARCHAR(100) (Metin)</option>
                    <option value="VARCHAR(255)">VARCHAR(255) (Uzun Metin)</option>
                    <option value="TEXT">TEXT (Çok Uzun Metin)</option>
                    <option value="DATE">DATE (Tarih)</option>
                    <option value="DATETIME">DATETIME (Tarih ve Saat)</option>
                    <option value="DECIMAL(10,2)">DECIMAL(10,2) (Ondalık Sayı)</option>
                    <option value="BOOLEAN">BOOLEAN (Evet/Hayır)</option>
                </select>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
                <label style="display: flex; align-items: center;">
                    <input type="checkbox" name="fields[0][primary]"> Birincil Anahtar
                </label>
                <label style="display: flex; align-items: center;">
                    <input type="checkbox" name="fields[0][not_null]"> Boş Olamaz
                </label>
                <label style="display: flex; align-items: center;">
                    <input type="checkbox" name="fields[0][unique]"> Benzersiz Olsun
                </label>
            </div>
            <button type="button" class="btn btn-small" style="margin-top: 10px;" onclick="removeField(0)">🗑️ Sil</button>
        </div>
    </div>
    
    <button type="button" class="btn" style="margin: 20px 0;" onclick="addField()">➕ Sütun Ekle</button>
    
    <div style="margin-top: 20px; text-align: right;">
        <button type="submit" class="btn btn-success">✅ Tabloyu Oluştur</button>
    </div>
</form>

<script>
let fieldCount = 1;

function addField() {
    const container = document.getElementById('fields-container');
    const fieldNumber = fieldCount + 1;
    
    const fieldHtml = `
        <div class="field-group">
            <h4>Sütun ${fieldNumber}</h4>
            <div class="form-group">
                <label>Sütun Adı:</label>
                <input type="text" name="fields[${fieldCount}][name]" placeholder="ad, email, vb.">
            </div>
            <div class="form-group">
                <label>Veri Tipi:</label>
                <select name="fields[${fieldCount}][type]">
                    <option value="INT">INT (Sayı)</option>
                    <option value="VARCHAR(100)" selected>VARCHAR(100) (Metin)</option>
                    <option value="VARCHAR(255)">VARCHAR(255) (Uzun Metin)</option>
                    <option value="TEXT">TEXT (Çok Uzun Metin)</option>
                    <option value="DATE">DATE (Tarih)</option>
                    <option value="DATETIME">DATETIME (Tarih ve Saat)</option>
                    <option value="DECIMAL(10,2)">DECIMAL(10,2) (Ondalık Sayı)</option>
                    <option value="BOOLEAN">BOOLEAN (Evet/Hayır)</option>
                </select>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
                <label style="display: flex; align-items: center;">
                    <input type="checkbox" name="fields[${fieldCount}][primary]"> Birincil Anahtar
                </label>
                <label style="display: flex; align-items: center;">
                    <input type="checkbox" name="fields[${fieldCount}][not_null]"> Boş Olamaz
                </label>
                <label style="display: flex; align-items: center;">
                    <input type="checkbox" name="fields[${fieldCount}][unique]"> Benzersiz Olsun
                </label>
            </div>
            <button type="button" class="btn btn-small" style="margin-top: 10px;" onclick="removeField(${fieldCount})">🗑️ Sil</button>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', fieldHtml);
    fieldCount++;
}

function removeField(index) {
    const fieldGroup = event.target.closest('.field-group');
    if (fieldGroup) {
        fieldGroup.remove();
    }
}
</script>
