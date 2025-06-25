<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ViPOS Dashboard Test</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .test-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
        }
        
        .test-card h1 {
            color: #1f2937;
            margin-bottom: 1rem;
            font-size: 2rem;
        }
        
        .test-card p {
            color: #6b7280;
            margin-bottom: 1.5rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .stat-item {
            background: #f9fafb;
            padding: 1rem;
            border-radius: 8px;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1f2937;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        .button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="test-card">
        <h1>🎯 ViPOS Dashboard Test</h1>
        <p>Dashboard CSS và JavaScript đang hoạt động tốt!</p>
        
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value">{{ $stats['today']['sales']['amount'] ?? 0 }}</div>
                <div class="stat-label">Doanh thu hôm nay</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $stats['today']['transactions']['count'] ?? 0 }}</div>
                <div class="stat-label">Giao dịch hôm nay</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $stats['general']['active_sessions'] ?? 0 }}</div>
                <div class="stat-label">Ca đang hoạt động</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $stats['general']['total_products'] ?? 0 }}</div>
                <div class="stat-label">Tổng sản phẩm</div>
            </div>
        </div>
        
        <a href="/admin/vipos" class="button">Đi tới POS Terminal</a>
    </div>
</body>
</html>
