<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ViPOS Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue@3.3.4/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
                .vipos-dashboard {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
            width: 100%;
            position: relative;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 35px 60px rgba(0, 0, 0, 0.15);
        }
        
        .stat-card {
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
            background-size: 300% 100%;
            animation: gradient-flow 3s ease infinite;
        }
        
        @keyframes gradient-flow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .pulse-animation {
            animation: pulse-glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes pulse-glow {
            from { box-shadow: 0 0 20px rgba(102, 126, 234, 0.3); }
            to { box-shadow: 0 0 30px rgba(102, 126, 234, 0.6); }
        }
        
        .number-counter {
            font-variant-numeric: tabular-nums;
            font-feature-settings: 'tnum';
            transition: transform 0.2s ease;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: transparent;
        }
        
        .action-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .action-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .action-button:hover::before {
            left: 100%;
        }
        
        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            animation: status-blink 2s ease-in-out infinite;
        }
        
        @keyframes status-blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .chart-container {
            position: relative;
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
        }
        
        .floating-element {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .quick-action-card {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 1.5rem;
            text-decoration: none !important;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            position: relative;
            overflow: hidden;
            display: block;
        }
        
        .quick-action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .quick-action-card:hover::before {
            opacity: 1;
        }
        
        .quick-action-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15) !important;
            text-decoration: none !important;
        }
        
        .metric-display {
            font-size: 2.5rem !important;
            font-weight: 800 !important;
            line-height: 1 !important;
            letter-spacing: -0.025em;
            color: #1f2937 !important;
        }
        
        .trend-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .trend-up {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }
        
        .trend-down {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        /* Fix Vue.js display issues */
        [v-cloak] {
            display: none !important;
        }
        
        /* Ensure proper text colors */
        .text-gray-900 {
            color: #111827 !important;
        }
        
        .text-gray-600 {
            color: #4b5563 !important;
        }
        
        .text-gray-500 {
            color: #6b7280 !important;
        }
        
        /* Fix gradient backgrounds */
        .bg-gradient-to-br {
            background-image: linear-gradient(to bottom right, var(--tw-gradient-stops)) !important;
        }
        
        .from-blue-500 {
            --tw-gradient-from: #3b82f6;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgb(59 130 246 / 0));
        }
        
        .to-blue-600 {
            --tw-gradient-to: #2563eb;
        }
        
        .from-green-500 {
            --tw-gradient-from: #10b981;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgb(16 185 129 / 0));
        }
        
        .to-green-600 {
            --tw-gradient-to: #059669;
        }
        
        .from-purple-500 {
            --tw-gradient-from: #8b5cf6;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgb(139 92 246 / 0));
        }
        
        .to-purple-600 {
            --tw-gradient-to: #7c3aed;
        }
        
        .from-orange-500 {
            --tw-gradient-from: #f97316;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgb(249 115 22 / 0));
        }
        
        .to-orange-600 {
            --tw-gradient-to: #ea580c;
        }
        
        /* Basic Tailwind utility classes */
        .fixed { position: fixed; }
        .relative { position: relative; }
        .absolute { position: absolute; }
        .top-4 { top: 1rem; }
        .right-4 { right: 1rem; }
        .z-50 { z-index: 50; }
        .z-10 { z-index: 10; }
        .inline-flex { display: inline-flex; }
        .flex { display: flex; }
        .grid { display: grid; }
        .hidden { display: none; }
        .items-center { align-items: center; }
        .justify-center { justify-content: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 0.75rem; }
        .gap-4 { gap: 1rem; }
        .gap-6 { gap: 1.5rem; }
        .gap-8 { gap: 2rem; }
        .w-5 { width: 1.25rem; }
        .w-6 { width: 1.5rem; }
        .w-7 { width: 1.75rem; }
        .w-8 { width: 2rem; }
        .w-10 { width: 2.5rem; }
        .w-12 { width: 3rem; }
        .w-14 { width: 3.5rem; }
        .w-16 { width: 4rem; }
        .w-2 { width: 0.5rem; }
        .w-4 { width: 1rem; }
        .h-5 { height: 1.25rem; }
        .h-6 { height: 1.5rem; }
        .h-7 { height: 1.75rem; }
        .h-8 { height: 2rem; }
        .h-10 { height: 2.5rem; }
        .h-12 { height: 3rem; }
        .h-14 { height: 3.5rem; }
        .h-16 { height: 4rem; }
        .h-2 { height: 0.5rem; }
        .h-4 { height: 1rem; }
        .h-80 { height: 20rem; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
        .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
        .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
        .p-4 { padding: 1rem; }
        .p-6 { padding: 1.5rem; }
        .p-8 { padding: 2rem; }
        .mb-1 { margin-bottom: 0.25rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mb-8 { margin-bottom: 2rem; }
        .mb-10 { margin-bottom: 2.5rem; }
        .mt-1 { margin-top: 0.25rem; }
        .mt-2 { margin-top: 0.5rem; }
        .mr-2 { margin-right: 0.5rem; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .rounded-xl { border-radius: 0.75rem; }
        .rounded-2xl { border-radius: 1rem; }
        .rounded-lg { border-radius: 0.5rem; }
        .rounded-full { border-radius: 9999px; }
        .text-white { color: #ffffff; }
        .text-gray-700 { color: #374151; }
        .text-gray-800 { color: #1f2937; }
        .text-gray-900 { color: #111827; }
        .text-gray-600 { color: #4b5563; }
        .text-gray-500 { color: #6b7280; }
        .text-gray-400 { color: #9ca3af; }
        .text-green-600 { color: #16a34a; }
        .text-blue-600 { color: #2563eb; }
        .text-blue-800 { color: #1e40af; }
        .text-indigo-600 { color: #4f46e5; }
        .text-amber-600 { color: #d97706; }
        .text-sm { font-size: 0.875rem; line-height: 1.25rem; }
        .text-lg { font-size: 1.125rem; line-height: 1.75rem; }
        .text-xl { font-size: 1.25rem; line-height: 1.75rem; }
        .text-2xl { font-size: 1.5rem; line-height: 2rem; }
        .text-4xl { font-size: 2.25rem; line-height: 2.5rem; }
        .text-xs { font-size: 0.75rem; line-height: 1rem; }
        .font-medium { font-weight: 500; }
        .font-semibold { font-weight: 600; }
        .font-bold { font-weight: 700; }
        .font-extrabold { font-weight: 800; }
        .leading-none { line-height: 1; }
        .shadow-lg { box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1); }
        .shadow-xl { box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1); }
        .transition-all { transition-property: all; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }
        .transition-colors { transition-property: color, background-color, border-color, text-decoration-color, fill, stroke; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }
        .transition-shadow { transition-property: box-shadow; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }
        .duration-300 { transition-duration: 300ms; }
        .hover\:text-gray-900:hover { color: #111827; }
        .hover\:text-blue-800:hover { color: #1e40af; }
        .hover\:text-indigo-600:hover { color: #4f46e5; }
        .hover\:text-blue-600:hover { color: #2563eb; }
        .hover\:text-green-600:hover { color: #16a34a; }
        .hover\:shadow-lg:hover { box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1); }
        .hover\:shadow-xl:hover { box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1); }
        .hover\:bg-gray-100:hover { background-color: #f3f4f6; }
        .hover\:scale-105:hover { transform: scale(1.05); }
        .bg-gray-50 { background-color: #f9fafb; }
        .bg-gray-100 { background-color: #f3f4f6; }
        .bg-green-100 { background-color: #dcfce7; }
        .bg-amber-100 { background-color: #fef3c7; }
        .bg-blue-100 { background-color: #dbeafe; }
        .bg-green-500 { background-color: #22c55e; }
        .bg-amber-500 { background-color: #f59e0b; }
        .bg-blue-500 { background-color: #3b82f6; }
        .bg-purple-500 { background-color: #a855f7; }
        .bg-orange-500 { background-color: #f97316; }
        .bg-gray-200 { background-color: #e5e7eb; }
        .border-l-4 { border-left-width: 4px; }
        .border-green-500 { border-color: #22c55e; }
        .border-amber-500 { border-color: #f59e0b; }
        .cursor-pointer { cursor: pointer; }
        .overflow-hidden { overflow: hidden; }
        .truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .space-y-3 > :not([hidden]) ~ :not([hidden]) { margin-top: 0.75rem; }
        .space-y-4 > :not([hidden]) ~ :not([hidden]) { margin-top: 1rem; }
        .space-y-6 > :not([hidden]) ~ :not([hidden]) { margin-top: 1.5rem; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .capitalize { text-transform: capitalize; }
        .flex-1 { flex: 1 1 0%; }
        .flex-shrink-0 { flex-shrink: 0; }
        .min-w-0 { min-width: 0px; }
        .max-w-7xl { max-width: 80rem; }
        
        /* Grid classes */
        .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
        
        /* Responsive classes */
        @media (min-width: 768px) {
            .md\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        
        @media (min-width: 1024px) {
            .lg\:grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
            .lg\:grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .lg\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .lg\:col-span-2 { grid-column: span 2 / span 2; }
            .lg\:flex-row { flex-direction: row; }
            .lg\:items-center { align-items: center; }
            .lg\:justify-between { justify-content: space-between; }
        }
        
        .flex-col { flex-direction: column; }
        
        /* Gradient backgrounds for buttons */
        .bg-gradient-to-r { background-image: linear-gradient(to right, var(--tw-gradient-stops)); }
        .bg-gradient-to-br { background-image: linear-gradient(to bottom right, var(--tw-gradient-stops)); }
        
        .from-amber-500 { --tw-gradient-from: #f59e0b; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgb(245 158 11 / 0)); }
        .to-orange-500 { --tw-gradient-to: #f97316; }
        .from-indigo-500 { --tw-gradient-from: #6366f1; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgb(99 102 241 / 0)); }
        .to-indigo-600 { --tw-gradient-to: #4f46e5; }
        
    </style>
</head>
<body>
    <div class="vipos-dashboard" id="pos-dashboard" v-cloak>
            <!-- Close Button -->
            <div class="fixed top-4 right-4 z-50">
                <a href="{{ route('admin.dashboard.index') }}" 
                   class="glass-card inline-flex items-center gap-2 px-4 py-2 rounded-xl text-gray-700 hover:text-gray-900 transition-all hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="font-medium">Quay lại Admin</span>
                </a>
            </div>
            
            <!-- Main Container -->
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="glass-card rounded-2xl p-8 mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold gradient-text mb-2">
                                ViPOS Dashboard
                            </h1>
                            <p class="text-gray-600 text-lg">
                                Chào mừng trở lại! Quản lý hoạt động POS của bạn một cách thông minh.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-center">
                            <div class="text-sm text-gray-500 mb-1">Hôm nay</div>
                            <div class="text-xl font-bold text-gray-800">{{ date('d/m/Y') }}</div>
                            <div class="text-sm text-gray-500">{{ date('l') }}</div>
                        </div>
                        <button @click="refreshData" class="action-button text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Làm mới dữ liệu
                        </button>
                    </div>
                </div>
            </div>

            <!-- Session Status Alert -->
            <div v-if="stats.current_session" class="glass-card rounded-2xl p-6 mb-8 pulse-animation border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <div class="status-indicator bg-green-500"></div>
                                Ca làm việc đang hoạt động
                            </h3>
                            <p class="text-gray-600 mt-1">
                                Đã mở <span class="font-semibold">@{{ stats.current_session?.duration }}</span> • 
                                <span class="font-semibold">@{{ stats.current_session?.transactions.count }}</span> giao dịch • 
                                <span class="font-semibold text-green-600">@{{ stats.current_session?.sales.formatted }}</span>
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin.vipos.index') }}" class="action-button text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Mở POS Terminal
                    </a>
                </div>
            </div>

            <!-- No Session Alert -->
            <div v-else class="glass-card rounded-2xl p-6 mb-8 border-l-4 border-amber-500">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <div class="status-indicator bg-amber-500"></div>
                                Chưa có ca làm việc
                            </h3>
                            <p class="text-gray-600 mt-1">
                                Bạn cần mở ca làm việc để bắt đầu hoạt động POS
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin.vipos.sessions.index') }}" class="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Mở ca mới
                    </a>
                </div>
            </div>

            <!-- Statistics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
                <!-- Today's Sales -->
                <div class="stat-card glass-card rounded-2xl p-6 floating-element">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div v-if="stats.today?.sales.change !== undefined" class="trend-indicator" :class="stats.today.sales.change >= 0 ? 'trend-up' : 'trend-down'">
                            <svg v-if="stats.today.sales.change >= 0" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <svg v-else class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            @{{ Math.abs(stats.today.sales.change).toFixed(1) }}%
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-2">Doanh thu hôm nay</h3>
                        <p class="metric-display number-counter text-gray-900">@{{ formatCurrency(stats.today?.sales.amount || 0) }}</p>
                        <p class="text-sm text-gray-500 mt-2">@{{ stats.today?.transactions.count || 0 }} giao dịch</p>
                    </div>
                </div>

                <!-- This Week Sales -->
                <div class="stat-card glass-card rounded-2xl p-6 floating-element" style="animation-delay: 0.1s;">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-2">Doanh thu tuần này</h3>
                        <p class="metric-display number-counter text-gray-900">@{{ formatCurrency(stats.week?.sales.amount || 0) }}</p>
                        <p class="text-sm text-gray-500 mt-2">@{{ stats.week?.transactions.count || 0 }} giao dịch</p>
                    </div>
                </div>

                <!-- Active Sessions -->
                <div class="stat-card glass-card rounded-2xl p-6 floating-element" style="animation-delay: 0.2s;">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-2">Ca đang hoạt động</h3>
                        <p class="metric-display number-counter text-gray-900">@{{ stats.general?.active_sessions || 0 }}</p>
                        <p class="text-sm text-gray-500 mt-2">@{{ stats.general?.total_sessions_today || 0 }} ca hôm nay</p>
                    </div>
                </div>

                <!-- Products Count -->
                <div class="stat-card glass-card rounded-2xl p-6 floating-element" style="animation-delay: 0.3s;">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-2">Tổng sản phẩm</h3>
                        <p class="metric-display number-counter text-gray-900">@{{ stats.general?.total_products || 0 }}</p>
                        <p class="text-sm text-gray-500 mt-2">Có sẵn để bán</p>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                <!-- Sales Chart -->
                <div class="lg:col-span-2">
                    <div class="chart-container">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Biểu đồ doanh thu 7 ngày</h3>
                                <p class="text-gray-600">Theo dõi xu hướng bán hàng của bạn</p>
                            </div>
                            <div class="flex items-center gap-6 text-sm">
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full"></div>
                                    <span class="font-medium text-gray-700">Doanh thu</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 bg-gradient-to-r from-green-500 to-green-600 rounded-full"></div>
                                    <span class="font-medium text-gray-700">Giao dịch</span>
                                </div>
                            </div>
                        </div>
                        <div class="h-80 relative">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="space-y-6">
                    <div class="glass-card rounded-2xl p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            Thao tác nhanh
                        </h3>
                        
                        <div class="space-y-4">
                            <a href="{{ route('admin.vipos.index') }}" class="quick-action-card group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">Mở POS Terminal</h4>
                                        <p class="text-sm text-gray-600">Bắt đầu hoạt động bán hàng</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </a>

                            <a href="{{ route('admin.vipos.sessions.index') }}" class="quick-action-card group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors">Quản lý ca làm việc</h4>
                                        <p class="text-sm text-gray-600">Mở/đóng ca làm việc</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </a>

                            <a href="{{ route('admin.vipos.transactions.index') }}" class="quick-action-card group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900 group-hover:text-green-600 transition-colors">Lịch sử giao dịch</h4>
                                        <p class="text-sm text-gray-600">Xem chi tiết giao dịch POS</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Session Info -->
                    <div v-if="stats.current_session" class="glass-card rounded-2xl p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            Thông tin ca làm việc
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="font-medium text-gray-700">Thời gian mở</span>
                                </div>
                                <span class="font-bold text-gray-900">@{{ stats.current_session?.duration }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    <span class="font-medium text-gray-700">Số giao dịch</span>
                                </div>
                                <span class="font-bold text-gray-900">@{{ stats.current_session?.transactions.count }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                    <span class="font-medium text-gray-700">Doanh thu ca</span>
                                </div>
                                <span class="font-bold text-green-600">@{{ stats.current_session?.sales.formatted }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                                    <span class="font-medium text-gray-700">Tiền mặt hiện có</span>
                                </div>
                                <span class="font-bold text-gray-900">@{{ stats.current_session?.cash_on_hand.formatted }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Transactions -->
                <div class="glass-card rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Giao dịch gần đây</h3>
                        </div>
                        <a href="{{ route('admin.vipos.transactions.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                            Xem tất cả →
                        </a>
                    </div>
                    
                    <div class="space-y-3">
                        <div v-if="!stats.recent_transactions?.length" class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Chưa có giao dịch nào</h4>
                            <p class="text-gray-600 mb-4">Bắt đầu sử dụng POS để xem giao dịch ở đây</p>
                            <a href="{{ route('admin.vipos.index') }}" class="action-button text-white px-6 py-2 rounded-lg font-semibold">
                                Bắt đầu bán hàng
                            </a>
                        </div>
                        
                        <div v-for="transaction in stats.recent_transactions" :key="transaction.id" 
                             class="group p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-300 cursor-pointer">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center text-white text-xs font-bold">
                                            #
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-sm">@{{ transaction.transaction_number }}</p>
                                            <p class="text-xs text-gray-600">@{{ transaction.customer_name }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900 text-sm">@{{ transaction.formatted_total }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full capitalize">
                                            @{{ transaction.payment_method }}
                                        </span>
                                        <span class="text-xs text-gray-500">@{{ transaction.time_ago }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="glass-card rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Top sản phẩm tuần này</h3>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div v-if="!stats.top_products?.length" class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Chưa có dữ liệu bán hàng</h4>
                            <p class="text-gray-600">Bắt đầu bán hàng để xem thống kê sản phẩm</p>
                        </div>
                        
                        <div v-for="(product, index) in stats.top_products" :key="product.id" 
                             class="group p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-300">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                                        @{{ index + 1 }}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-gray-900 text-sm truncate">@{{ product.name }}</h4>
                                    <p class="text-xs text-gray-600 mt-1">SKU: @{{ product.sku }}</p>
                                    <div class="flex items-center gap-4 mt-2">
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                            <span class="text-xs font-medium text-gray-700">@{{ product.quantity }} SP</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                            <span class="text-xs font-medium text-green-600">@{{ product.formatted_revenue }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="w-16 bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 h-2 rounded-full transition-all duration-500"
                                             :style="{ width: Math.min((product.quantity / (stats.top_products?.[0]?.quantity || 1)) * 100, 100) + '%' }">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <script>
        const { createApp } = Vue;

            createApp({
                data() {
                    return {
                        loading: false,
                        stats: @json($stats ?? []),
                        chart: null
                    }
                },
                mounted() {
                    this.initChart();
                    this.animateNumbers();
                    // Auto refresh every 5 minutes
                    setInterval(() => {
                        this.refreshData();
                    }, 300000);
                },
                methods: {
                    async refreshData() {
                        this.loading = true;
                        try {
                            const response = await fetch("/admin/vipos/dashboard/stats");
                            const data = await response.json();
                            this.stats = data;
                            this.updateChart();
                            this.animateNumbers();
                        } catch (error) {
                            console.error('Error refreshing data:', error);
                        } finally {
                            this.loading = false;
                        }
                    },
                    
                    formatCurrency(amount) {
                        return new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0,
                        }).format(amount);
                    },
                    
                    animateNumbers() {
                        // Simple number animation for metrics
                        const elements = document.querySelectorAll('.number-counter');
                        elements.forEach(el => {
                            el.style.transform = 'scale(1.05)';
                            setTimeout(() => {
                                el.style.transform = 'scale(1)';
                            }, 200);
                        });
                    },
                    
                    initChart() {
                        const ctx = document.getElementById('salesChart');
                        if (!ctx || !this.stats.chart) return;

                        // Gradient backgrounds
                        const salesGradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
                        salesGradient.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
                        salesGradient.addColorStop(1, 'rgba(59, 130, 246, 0.1)');

                        const transactionGradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
                        transactionGradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
                        transactionGradient.addColorStop(1, 'rgba(16, 185, 129, 0.1)');

                        this.chart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: this.stats.chart.labels,
                                datasets: [
                                    {
                                        label: 'Doanh thu (VND)',
                                        data: this.stats.chart.sales,
                                        borderColor: 'rgb(59, 130, 246)',
                                        backgroundColor: salesGradient,
                                        borderWidth: 3,
                                        fill: true,
                                        tension: 0.4,
                                        pointBackgroundColor: 'rgb(59, 130, 246)',
                                        pointBorderColor: '#ffffff',
                                        pointBorderWidth: 3,
                                        pointRadius: 6,
                                        pointHoverRadius: 8,
                                        yAxisID: 'y'
                                    },
                                    {
                                        label: 'Số giao dịch',
                                        data: this.stats.chart.transactions,
                                        borderColor: 'rgb(16, 185, 129)',
                                        backgroundColor: transactionGradient,
                                        borderWidth: 3,
                                        fill: true,
                                        tension: 0.4,
                                        pointBackgroundColor: 'rgb(16, 185, 129)',
                                        pointBorderColor: '#ffffff',
                                        pointBorderWidth: 3,
                                        pointRadius: 6,
                                        pointHoverRadius: 8,
                                        yAxisID: 'y1'
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    intersect: false,
                                    mode: 'index'
                                },
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        titleColor: '#ffffff',
                                        bodyColor: '#ffffff',
                                        borderColor: 'rgba(255, 255, 255, 0.1)',
                                        borderWidth: 1,
                                        cornerRadius: 8,
                                        padding: 12,
                                        displayColors: true,
                                        callbacks: {
                                            label: function(context) {
                                                if (context.datasetIndex === 0) {
                                                    return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN', {
                                                        style: 'currency',
                                                        currency: 'VND',
                                                        minimumFractionDigits: 0
                                                    }).format(context.raw);
                                                } else {
                                                    return 'Giao dịch: ' + context.raw;
                                                }
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: {
                                            display: false
                                        },
                                        border: {
                                            display: false
                                        },
                                        ticks: {
                                            color: '#6b7280',
                                            font: {
                                                size: 12,
                                                family: 'Inter'
                                            }
                                        }
                                    },
                                    y: {
                                        type: 'linear',
                                        display: true,
                                        position: 'left',
                                        grid: {
                                            color: 'rgba(156, 163, 175, 0.2)',
                                            drawBorder: false
                                        },
                                        border: {
                                            display: false
                                        },
                                        ticks: {
                                            color: '#6b7280',
                                            font: {
                                                size: 11,
                                                family: 'Inter'
                                            },
                                            callback: function(value) {
                                                return new Intl.NumberFormat('vi-VN', {
                                                    notation: 'compact',
                                                    compactDisplay: 'short'
                                                }).format(value);
                                            }
                                        }
                                    },
                                    y1: {
                                        type: 'linear',
                                        display: true,
                                        position: 'right',
                                        grid: {
                                            drawOnChartArea: false,
                                        },
                                        border: {
                                            display: false
                                        },
                                        ticks: {
                                            color: '#6b7280',
                                            font: {
                                                size: 11,
                                                family: 'Inter'
                                            }
                                        }
                                    }
                                },
                                elements: {
                                    point: {
                                        hoverBorderWidth: 4
                                    }
                                }
                            }
                        });
                    },
                    
                    updateChart() {
                                        if (!this.chart || !this.stats.chart) return;

                        this.chart.data.labels = this.stats.chart.labels;
                        this.chart.data.datasets[0].data = this.stats.chart.sales;
                        this.chart.data.datasets[1].data = this.stats.chart.transactions;
                        this.chart.update('active');
                    }
                }
            }).mount('#pos-dashboard');
        </script>
    
    </div> <!-- Close dashboard div -->
</body>
</html>
