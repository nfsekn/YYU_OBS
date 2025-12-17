<?php
/**
 * Veri Ekle
 */

$selected_table = $_GET['table'] ?? $_POST['table'] ?? null;
$msg = '';

// Veri ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $selected_table = $_POST['table'];
    
    if ($selected_table && in_array($selected_table, $tables)) {
        // Sütunları al
        $col_result = $conn->query("DESCRIBE $selected_table");
        $columns = [];
        while ($col = $col_result->fetch_assoc()) {
            $columns[$col['Field']] = $col['Type'];
        }
        
        // INSERT sorgusu oluştur
        $fields = [];
        $values = [];
        
        foreach ($columns as $field => $type) {
            if (isset($_POST[$field]) && $_POST[$field] !== '') {
                $fields[] = $field;
                $values[] = "'" . $conn->real_escape_string($_POST[$field]) . "'";
            }
        }
        
        if (!empty($fields)) {
            $sql = "INSERT INTO $selected_table (" . implode(',', $fields) . ") VALUES (" . implode(',', $values) . ")";
            
            if ($conn->query($sql)) {
                $msg = '<div class="alert alert-success">✅ Veri başarıyla eklendi!</div>';
            } else {
                $msg = '<div class="alert alert-error">❌ Hata: ' . $conn->error . '</div>';
            }
        } else {
            $msg = '<div class="alert alert-error">❌ Lütfen en az bir veri girin.</div>';
        }
    }
}

// Seçili tablo varsa sütunları al
$table_columns = [];
if ($selected_table && in_array($selected_table, $tables)) {
    $col_result = $conn->query("DESCRIBE $selected_table");
    while ($col = $col_result->fetch_assoc()) {
        $table_columns[] = $col;
    }
}
?>

<h2>➕ Veri Ekle</h2>

<?php echo $msg; ?>

<div style="margin-bottom: 20px;">
    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Tablo Seç:</label>
    <select onchange="location.href='?page=add_data&table=' + this.value" style="padding: 10px; border-radius: 5px; border: 1px solid #ddd; font-size: 1em;">
        <option value="">-- Tablo Seçin --</option>
        <?php foreach ($tables as $table): ?>
            <option value="<?php echo urlencode($table); ?>" <?php echo ($selected_table === $table) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($table); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<?php if ($selected_table && in_array($selected_table, $tables)): ?>
    <form method="POST" style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="table" value="<?php echo htmlspecialchars($selected_table); ?>">
        
        <h3 style="margin-bottom: 20px;">📝 <?php echo htmlspecialchars($selected_table); ?> - Yeni Veri</h3>
        
        <div class="form-columns">
            <?php foreach ($table_columns as $col): ?>
                <div class="form-group">
                    <label for="<?php echo $col['Field']; ?>">
                        <?php echo htmlspecialchars($col['Field']); ?>
                        <span style="color: #999; font-size: 0.9em;">(<?php echo $col['Type']; ?>)</span>
                    </label>
                    
                    <?php if (strpos($col['Type'], 'int') !== false): ?>
                        <input type="number" name="<?php echo $col['Field']; ?>" id="<?php echo $col['Field']; ?>">
                    <?php elseif (strpos($col['Type'], 'date') !== false): ?>
                        <input type="date" name="<?php echo $col['Field']; ?>" id="<?php echo $col['Field']; ?>">
                    <?php elseif (strpos($col['Type'], 'text') !== false): ?>
                        <textarea name="<?php echo $col['Field']; ?>" id="<?php echo $col['Field']; ?>" rows="3"></textarea>
                    <?php else: ?>
                        <input type="text" name="<?php echo $col['Field']; ?>" id="<?php echo $col['Field']; ?>">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <button type="submit" class="btn btn-success" style="margin-top: 20px;">✅ Veri Ekle</button>
    </form>
<?php else: ?>
    <div class="no-data">
        <h3>👆 Yukarıdan bir tablo seçin</h3>
        <p>Veri eklemek için önce bir tablo seçmelisiniz.</p>
    </div>
<?php endif; ?>
