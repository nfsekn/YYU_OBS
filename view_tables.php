<?php
// Sütunları al
        $col_result = $conn->query("DESCRIBE $selected_table");
        $table_columns = [];
        while ($col = $col_result->fetch_assoc()) {
            $table_columns[] = $col['Field'];
        }
        
        // Verileri al
        $data_result = $conn->query("SELECT * FROM $selected_table LIMIT 100");
        $table_data = [];
        $row_count = $data_result->num_rows;
        
        while ($row = $data_result->fetch_assoc()) {
            $table_data[] = $row;
        }
    }
    ?>
    
    <h2>📋 Tabloları Görüntüle</h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; margin: 20px 0;">
        <?php foreach ($tables as $table): ?>
            <a href="?page=view_tables&table=<?php echo urlencode($table); ?>" 
               style="padding: 15px; background: <?php echo ($selected_table === $table) ? '#667eea' : '#f8f9fa'; ?>; 
                       color: <?php echo ($selected_table === $table) ? 'white' : '#333'; ?>; 
                       text-decoration: none; border-radius: 5px; text-align: center; 
                       transition: all 0.3s; border: 1px solid #ddd;"
               onmouseover="this.style.background='#667eea'; this.style.color='white';"
               onmouseout="this.style.background='<?php echo ($selected_table === $table) ? '#667eea' : '#f8f9fa'; ?>'; 
                           this.style.color='<?php echo ($selected_table === $table) ? 'white' : '#333'; ?>';">
                📊 <?php echo htmlspecialchars($table); ?>
            </a>
        <?php endforeach; ?>
    </div>
    
    <?php if ($selected_table && in_array($selected_table, $tables)): ?>
        <div style="background: #e8f4f8; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #667eea;">
            <h3>📊 <?php echo htmlspecialchars($selected_table); ?></h3>
            <p>Toplam Kayıt: <strong><?php echo $row_count; ?></strong> | Sütun Sayısı: <strong><?php echo count($table_columns); ?></strong></p>
        </div>
        
        <?php if (empty($table_data)): ?>
            <div style="background: #fff3cd; padding: 15px; border-radius: 5px; color: #856404; border: 1px solid #ffc107;">
                ⚠️ Bu tabloda veri bulunmamaktadır.
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <?php foreach ($table_columns as $column): ?>
                                <th><?php echo htmlspecialchars($column); ?></th>
                            <?php endforeach; ?>
                            <th style="background: #764ba2;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($table_data as $row): ?>
                            <tr>
                                <?php foreach ($table_columns as $column): ?>
                                    <td>
                                        <?php 
                                            $value = $row[$column];
                                            if ($value === null) {
                                                echo '<em style="color: #999;">NULL</em>';
                                            } else {
                                                echo htmlspecialchars(substr($value, 0, 50));
                                                if (strlen($value) > 50) echo '...';
                                            }
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                                <td>
                                    <div class="action-btns">
                                        <a href="?page=add_data&table=<?php echo urlencode($selected_table); ?>" 
                                           class="btn btn-success btn-small">✏️ Düzenle</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="no-data">
            <h3>👈 Sol taraftan bir tablo seçin</h3>
            <p>Veritabanındaki tabloları ve verilerini görüntülemek için yukarıdaki tablolardan birini seçin.</p>
        </div>
    <?php endif;
