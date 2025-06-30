<?php
require_once 'config.php';

// Cek login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Handle CRUD operations
$message = '';
$message_type = '';

// CREATE - Tambah produk
if (isset($_POST['add_product'])) {
    $nama_produk = clean_input($_POST['nama_produk']);
    $kategori = clean_input($_POST['kategori']);
    $merk = clean_input($_POST['merk']);
    $harga = clean_input($_POST['harga']);
    $stok = clean_input($_POST['stok']);
    $deskripsi = clean_input($_POST['deskripsi']);
    $gambar = clean_input($_POST['gambar']);
    
    try {
        $stmt = $conn->prepare("INSERT INTO produk_olahraga (nama_produk, kategori, merk, harga, stok, deskripsi, gambar) VALUES (:nama_produk, :kategori, :merk, :harga, :stok, :deskripsi, :gambar)");
        $stmt->bindParam(':nama_produk', $nama_produk);
        $stmt->bindParam(':kategori', $kategori);
        $stmt->bindParam(':merk', $merk);
        $stmt->bindParam(':harga', $harga);
        $stmt->bindParam(':stok', $stok);
        $stmt->bindParam(':deskripsi', $deskripsi);
        $stmt->bindParam(':gambar', $gambar);
        $stmt->execute();
        
        $message = "Produk berhasil ditambahkan!";
        $message_type = "success";
    } catch(PDOException $e) {
        $message = "Error: " . $e->getMessage();
        $message_type = "error";
    }
}

// UPDATE - Edit produk
if (isset($_POST['edit_product'])) {
    $id = clean_input($_POST['id']);
    $nama_produk = clean_input($_POST['nama_produk']);
    $kategori = clean_input($_POST['kategori']);
    $merk = clean_input($_POST['merk']);
    $harga = clean_input($_POST['harga']);
    $stok = clean_input($_POST['stok']);
    $deskripsi = clean_input($_POST['deskripsi']);
    $gambar = clean_input($_POST['gambar']);
    
    try {
        $stmt = $conn->prepare("UPDATE produk_olahraga SET nama_produk = :nama_produk, kategori = :kategori, merk = :merk, harga = :harga, stok = :stok, deskripsi = :deskripsi, gambar = :gambar WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nama_produk', $nama_produk);
        $stmt->bindParam(':kategori', $kategori);
        $stmt->bindParam(':merk', $merk);
        $stmt->bindParam(':harga', $harga);
        $stmt->bindParam(':stok', $stok);
        $stmt->bindParam(':deskripsi', $deskripsi);
        $stmt->bindParam(':gambar', $gambar);
        $stmt->execute();
        
        $message = "Produk berhasil diperbarui!";
        $message_type = "success";
    } catch(PDOException $e) {
        $message = "Error: " . $e->getMessage();
        $message_type = "error";
    }
}

// DELETE - Hapus produk
if (isset($_GET['delete'])) {
    $id = clean_input($_GET['delete']);
    
    try {
        $stmt = $conn->prepare("DELETE FROM produk_olahraga WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $message = "Produk berhasil dihapus!";
        $message_type = "success";
    } catch(PDOException $e) {
        $message = "Error: " . $e->getMessage();
        $message_type = "error";
    }
}

// SEARCH - Pencarian produk
$search = '';
$where_clause = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = clean_input($_GET['search']);
    $where_clause = "WHERE nama_produk LIKE :search OR kategori LIKE :search OR merk LIKE :search";
}

// READ - Ambil data produk
try {
    $sql = "SELECT * FROM produk_olahraga $where_clause ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    
    if ($where_clause) {
        $search_param = "%$search%";
        $stmt->bindParam(':search', $search_param);
    }
    
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $message = "Error: " . $e->getMessage();
    $message_type = "error";
}

// Get product for editing
$edit_product = null;
if (isset($_GET['edit'])) {
    $edit_id = clean_input($_GET['edit']);
    try {
        $stmt = $conn->prepare("SELECT * FROM produk_olahraga WHERE id = :id");
        $stmt->bindParam(':id', $edit_id);
        $stmt->execute();
        $edit_product = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $message = "Error: " . $e->getMessage();
        $message_type = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Toko Olahraga Online</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            line-height: 1.6;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            font-size: 1.5rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .message {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .section {
            background: white;
            margin-bottom: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .section-header {
            background: #f8f9fa;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #dee2e6;
            border-radius: 10px 10px 0 0;
        }
        
        .section-content {
            padding: 1.5rem;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-warning:hover {
            background: #e0a800;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .search-box {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .search-box input {
            flex: 1;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 5px;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }
        
        .stat-label {
            color: #666;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏃‍♂️ Dashboard Toko Olahraga</h1>
        <div class="user-info">
            <span>Selamat datang, <?php echo $_SESSION['admin_nama']; ?>!</span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Statistics -->
        <div class="stats">
            <?php
            try {
                $stmt = $conn->query("SELECT COUNT(*) as total FROM produk_olahraga");
                $total_products = $stmt->fetch()['total'];
                
                $stmt = $conn->query("SELECT SUM(stok) as total_stok FROM produk_olahraga");
                $total_stock = $stmt->fetch()['total_stok'];
                
                $stmt = $conn->query("SELECT COUNT(DISTINCT kategori) as total_kategori FROM produk_olahraga");
                $total_categories = $stmt->fetch()['total_kategori'];
                
                $stmt = $conn->query("SELECT COUNT(DISTINCT merk) as total_merk FROM produk_olahraga");
                $total_brands = $stmt->fetch()['total_merk'];
            } catch(PDOException $e) {
                $total_products = $total_stock = $total_categories = $total_brands = 0;
            }
            ?>
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_products; ?></div>
                <div class="stat-label">Total Produk</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_stock; ?></div>
                <div class="stat-label">Total Stok</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_categories; ?></div>
                <div class="stat-label">Kategori</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_brands; ?></div>
                <div class="stat-label">Merk</div>
            </div>
        </div>
        
        <!-- Form Tambah/Edit Produk -->
        <div class="section">
            <div class="section-header">
                <h2><?php echo $edit_product ? 'Edit Produk' : 'Tambah Produk Baru'; ?></h2>
            </div>
            <div class="section-content">
                <form method="POST" action="">
                    <?php if ($edit_product): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_product['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nama_produk">Nama Produk:</label>
                            <input type="text" id="nama_produk" name="nama_produk" value="<?php echo $edit_product ? htmlspecialchars($edit_product['nama_produk']) : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="kategori">Kategori:</label>
                            <select id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Sepatu" <?php echo ($edit_product && $edit_product['kategori'] == 'Sepatu') ? 'selected' : ''; ?>>Sepatu</option>
                                <option value="Pakaian" <?php echo ($edit_product && $edit_product['kategori'] == 'Pakaian') ? 'selected' : ''; ?>>Pakaian</option>
                                <option value="Bola" <?php echo ($edit_product && $edit_product['kategori'] == 'Bola') ? 'selected' : ''; ?>>Bola</option>
                                <option value="Raket" <?php echo ($edit_product && $edit_product['kategori'] == 'Raket') ? 'selected' : ''; ?>>Raket</option>
                                <option value="Aksesoris" <?php echo ($edit_product && $edit_product['kategori'] == 'Aksesoris') ? 'selected' : ''; ?>>Aksesoris</option>
                                <option value="Alat Fitness" <?php echo ($edit_product && $edit_product['kategori'] == 'Alat Fitness') ? 'selected' : ''; ?>>Alat Fitness</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="merk">Merk:</label>
                            <input type="text" id="merk" name="merk" value="<?php echo $edit_product ? htmlspecialchars($edit_product['merk']) : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="harga">Harga (Rp):</label>
                            <input type="number" id="harga" name="harga" step="0.01" value="<?php echo $edit_product ? $edit_product['harga'] : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="stok">Stok:</label>
                            <input type="number" id="stok" name="stok" value="<?php echo $edit_product ? $edit_product['stok'] : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="gambar">URL Gambar:</label>
                            <input type="text" id="gambar" name="gambar" value="<?php echo $edit_product ? htmlspecialchars($edit_product['gambar']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi:</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3"><?php echo $edit_product ? htmlspecialchars($edit_product['deskripsi']) : ''; ?></textarea>
                    </div>
                    
                    <div class="actions">
                        <button type="submit" name="<?php echo $edit_product ? 'edit_product' : 'add_product'; ?>" class="btn btn-primary">
                            <?php echo $edit_product ? 'Update Produk' : 'Tambah Produk'; ?>
                        </button>
                        <?php if ($edit_product): ?>
                            <a href="dashboard.php" class="btn btn-secondary">Batal</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Daftar Produk -->
        <div class="section">
            <div class="section-header">
                <h2>Daftar Produk Olahraga</h2>
            </div>
            <div class="section-content">
                <!-- Search Box -->
                <form method="GET" action="">
                    <div class="search-box">
                        <input type="text" name="search" placeholder="Cari produk, kategori, atau merk..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn btn-primary">Cari</button>
                        <?php if ($search): ?>
                            <a href="dashboard.php" class="btn btn-secondary">Reset</a>
                        <?php endif; ?>
                    </div>
                </form>
                
                <?php if ($search): ?>
                    <p>Hasil pencarian untuk: "<strong><?php echo htmlspecialchars($search); ?></strong>" (<?php echo count($products); ?> produk ditemukan)</p>
                <?php endif; ?>
                
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Merk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Gambar</th>
                                <th>Tanggal Ditambahkan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="9" style="text-align: center; padding: 2rem; color: #666;">
                                        <?php echo $search ? 'Tidak ada produk yang ditemukan.' : 'Belum ada produk yang ditambahkan.'; ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?php echo $product['id']; ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($product['nama_produk']); ?></strong>
                                            <?php if ($product['deskripsi']): ?>
                                                <br><small style="color: #666;"><?php echo substr(htmlspecialchars($product['deskripsi']), 0, 100) . (strlen($product['deskripsi']) > 100 ? '...' : ''); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($product['kategori']); ?></td>
                                        <td><?php echo htmlspecialchars($product['merk']); ?></td>
                                        <td><?php echo format_rupiah($product['harga']); ?></td>
                                        <td>
                                            <span style="<?php echo $product['stok'] < 5 ? 'color: #dc3545; font-weight: bold;' : ($product['stok'] < 10 ? 'color: #ffc107; font-weight: bold;' : 'color: #28a745;'); ?>">
                                                <?php echo $product['stok']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($product['gambar']): ?>
                                                <img src="<?php echo htmlspecialchars($product['gambar']); ?>" alt="<?php echo htmlspecialchars($product['nama_produk']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCA1MCA1MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjUwIiBoZWlnaHQ9IjUwIiBmaWxsPSIjRjVGNUY1Ii8+CjxwYXRoIGQ9Ik0yNSAyMEM3LjI2IDIwIDcuMjYgMzAgMjUgMzBTNDIuNzQgMjAgMjUgMjBaTTI1IDI1QzI2LjY2IDI1IDI4IDIzLjY2IDI4IDIyUzI2LjY2IDE5IDI1IDE5UzIyIDIwLjM0IDIyIDIyUzIzLjM0IDI1IDI1IDI1WiIgZmlsbD0iI0NDQyIvPgo8L3N2Zz4K';">
                                            <?php else: ?>
                                                <div style="width: 50px; height: 50px; background: #f5f5f5; border-radius: 5px; display: flex; align-items: center; justify-content: center; color: #ccc;">📷</div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($product['tanggal_ditambahkan'])); ?></td>
                                        <td>
                                            <div class="actions">
                                                <a href="dashboard.php?edit=<?php echo $product['id']; ?>" class="btn btn-warning" style="padding: 0.5rem; font-size: 0.875rem;">Edit</a>
                                                <a href="dashboard.php?delete=<?php echo $product['id']; ?>" class="btn btn-danger" style="padding: 0.5rem; font-size: 0.875rem;" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Auto hide message after 5 seconds
        setTimeout(function() {
            const message = document.querySelector('.message');
            if (message) {
                message.style.opacity = '0';
                message.style.transition = 'opacity 0.5s';
                setTimeout(function() {
                    message.remove();
                }, 500);
            }
        }, 5000);
        
        // Format currency input
        document.getElementById('harga').addEventListener('input', function(e) {
            let value = e.target.value;
            // Remove non-numeric characters except decimal point
            value = value.replace(/[^0-9.]/g, '');
            e.target.value = value;
        });
        
        // Confirm delete action
        function confirmDelete(id, name) {
            return confirm(`Apakah Anda yakin ingin menghapus produk "${name}"?`);
        }
    </script>
</body>
</html>