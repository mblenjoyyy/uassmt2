<?php
require_once 'config.php';

// Ambil produk terbaru untuk ditampilkan
try {
    $stmt = $conn->query("SELECT * FROM produk_olahraga ORDER BY tanggal_ditambahkan DESC LIMIT 6");
    $latest_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Ambil statistik
    $stmt = $conn->query("SELECT COUNT(*) as total FROM produk_olahraga");
    $total_products = $stmt->fetch()['total'];
    
    $stmt = $conn->query("SELECT COUNT(DISTINCT kategori) as total_kategori FROM produk_olahraga");
    $total_categories = $stmt->fetch()['total_kategori'];
    
    $stmt = $conn->query("SELECT COUNT(DISTINCT merk) as total_merk FROM produk_olahraga");
    $total_brands = $stmt->fetch()['total_merk'];
    
} catch(PDOException $e) {
    $latest_products = [];
    $total_products = $total_categories = $total_brands = 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Olahraga Online - Perlengkapan Olahraga Terlengkap</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
        }
        
        .nav-menu a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background 0.3s;
        }
        
        .nav-menu a:hover {
            background: rgba(255,255,255,0.2);
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
            text-align: center;
        }
        
        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-block;
        }
        
        .btn-primary {
            background: white;
            color: #667eea;
            font-weight: bold;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        
        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .btn-secondary:hover {
            background: white;
            color: #667eea;
        }
        
        /* Stats Section */
        .stats {
            background: #f8f9fa;
            padding: 3rem 0;
        }
        
        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            text-align: center;
        }
        
        .stat-item {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .stat-item:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 1.1rem;
            color: #666;
        }
        
        /* Products Section */
        .products {
            padding: 4rem 0;
        }
        
        .products-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 1rem;
        }
        
        .section-title p {
            font-size: 1.1rem;
            color: #666;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .product-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-image {
            height: 200px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #ccc;
        }
        
        .product-info {
            padding: 1.5rem;
        }
        
        .product-category {
            color: #667eea;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .product-name {
            font-size: 1.3rem;
            font-weight: bold;
            margin: 0.5rem 0;
            color: #333;
        }
        
        .product-brand {
            color: #666;
            margin-bottom: 1rem;
        }
        
        .product-price {
            font-size: 1.4rem;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 0.5rem;
        }
        
        .product-stock {
            font-size: 0.9rem;
            color: #666;
        }
        
        /* Features Section */
        .features {
            background: #f8f9fa;
            padding: 4rem 0;
        }
        
        .features-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
        
        .feature-item {
            text-align: center;
            padding: 2rem;
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .feature-title {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #333;
        }
        
        .feature-description {
            color: #666;
            line-height: 1.6;
        }
        
        /* Footer */
        .footer {
            background: #333;
            color: white;
            padding: 3rem 0 1rem;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
        
        .footer-section h3 {
            margin-bottom: 1rem;
            color: #667eea;
        }
        
        .footer-section ul {
            list-style: none;
        }
        
        .footer-section ul li {
            margin-bottom: 0.5rem;
        }
        
        .footer-section ul li a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-section ul li a:hover {
            color: #667eea;
        }
        
        .footer-bottom {
            border-top: 1px solid #555;
            margin-top: 2rem;
            padding-top: 1rem;
            text-align: center;
            color: #ccc;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }
            
            .nav-menu {
                flex-direction: column;
                gap: 1rem;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <div class="logo">🏃‍♂️ SportShop</div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="#home">Beranda</a></li>
                    <li><a href="#products">Produk</a></li>
                    <li><a href="#features">Layanan</a></li>
                    <li><a href="login.php">Admin Login</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-container">
            <h1>Toko Olahraga Online Terlengkap</h1>
            <p>Dapatkan perlengkapan olahraga berkualitas tinggi dengan harga terbaik. Dari sepatu lari hingga peralatan fitness, semua ada di sini!</p>
            <div class="cta-buttons">
                <a href="#products" class="btn btn-primary">Lihat Produk</a>
                <a href="login.php" class="btn btn-secondary">Admin Dashboard</a>
            </div>
        </div>
    </section>
    
    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-container">
            <div class="stat-item">
                <div class="stat-number"><?php echo $total_products; ?>+</div>
                <div class="stat-label">Produk Tersedia</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $total_categories; ?>+</div>
                <div class="stat-label">Kategori Produk</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $total_brands; ?>+</div>
                <div class="stat-label">Merk Terkenal</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Customer Support</div>
            </div>
        </div>
    </section>
    
    <!-- Products Section -->
    <section id="products" class="products">
        <div class="products-container">
            <div class="section-title">
                <h2>Produk Terbaru</h2>
                <p>Koleksi terbaru perlengkapan olahraga pilihan untuk kebutuhan aktivitas Anda</p>
            </div>
            
            <div class="products-grid">
                <?php if (empty($latest_products)): ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #666;">
                        <h3>Belum Ada Produk</h3>
                        <p>Produk akan segera ditambahkan. Silakan kembali lagi nanti.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($latest_products as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <?php if ($product['gambar']): ?>
                                    <img src="<?php echo htmlspecialchars($product['gambar']); ?>" alt="<?php echo htmlspecialchars($product['nama_produk']); ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.parentElement.innerHTML='🏃‍♂️';">
                                <?php else: ?>
                                    🏃‍♂️
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <div class="product-category"><?php echo htmlspecialchars($product['kategori']); ?></div>
                                <div class="product-name"><?php echo htmlspecialchars($product['nama_produk']); ?></div>
                                <div class="product-brand">Merk: <?php echo htmlspecialchars($product['merk']); ?></div>
                                <div class="product-price"><?php echo format_rupiah($product['harga']); ?></div>
                                <div class="product-stock">Stok: <?php echo $product['stok']; ?> pcs</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($latest_products)): ?>
                <div style="text-align: center; margin-top: 3rem;">
                    <a href="login.php" class="btn btn-primary">Lihat Semua Produk (Admin)</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Features Section -->
    <section id="features" class="features">
        <div class="features-container">
            <div class="feature-item">
                <div class="feature-icon">🚚</div>
                <div class="feature-title">Pengiriman Gratis</div>
                <div class="feature-description">Gratis ongkos kirim untuk pembelian di atas Rp 500.000 ke seluruh Indonesia</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">🏆</div>
                <div class="feature-title">Produk Original</div>
                <div class="feature-description">Semua produk dijamin 100% original dari distributor resmi</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">💳</div>
                <div class="feature-title">Pembayaran Aman</div>
                <div class="feature-description">Berbagai metode pembayaran yang aman dan terpercaya</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">🔄</div>
                <div class="feature-title">Garansi Return</div>
                <div class="feature-description">Garansi pengembalian barang dalam 7 hari jika tidak sesuai</div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>SportShop</h3>
                <p>Toko olahraga online terpercaya dengan koleksi lengkap perlengkapan olahraga berkualitas tinggi.</p>
            </div>
            <div class="footer-section">
                <h3>Kategori Produk</h3>
                <ul>
                    <li><a href="#">Sepatu Olahraga</a></li>
                    <li><a href="#">Pakaian Olahraga</a></li>
                    <li><a href="#">Peralatan Fitness</a></li>
                    <li><a href="#">Aksesoris Olahraga</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Layanan</h3>
                <ul>
                    <li><a href="#">Bantuan Pelanggan</a></li>
                    <li><a href="#">Panduan Pembelian</a></li>
                    <li><a href="#">Kebijakan Return</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Kontak</h3>
                <ul>
                    <li>📧 info@sportshop.com</li>
                    <li>📞 (021) 1234-5678</li>
                    <li>📍 Jakarta, Indonesia</li>
                    <li><a href="login.php">Admin Login</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 SportShop. Semua hak cipta dilindungi. | Dibuat untuk tugas PHP CRUD</p>
        </div>
    </footer>
    
    <script>
        // Smooth scrolling untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
         // Tambahkan CSS untuk animasi
        const style = document.createElement('style');
        style.innerHTML = `
            .animate-on-scroll {
                opacity: 0;
                transform: translateY(20px);
                transition: opacity 0.6s ease-out, transform 0.6s ease-out;
            }

            .animate-on-scroll.fade-in-up {
                opacity: 1;
                transform: translateY(0);
            }
        `;
        document.head.appendChild(style);

    </script>
</body>
</html>

// Animation on scroll
        const observerOptions = {
            threshold: 0.1,