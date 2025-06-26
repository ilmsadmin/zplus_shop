<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ trans('warranty::app.frontend.search.title') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .warranty-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .warranty-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        .status-expired {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-claimed {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-cancelled {
            background-color: #e2e3e5;
            color: #383d41;
        }
        .search-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h1 class="display-4 mb-3">
                        <i class="fas fa-shield-alt me-3"></i>
                        {{ trans('warranty::app.frontend.search.title') }}
                    </h1>
                    <p class="lead">{{ trans('warranty::app.frontend.search.description') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="container">
        <div class="search-container">
            <!-- Search Form -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form id="warrantySearchForm">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="searchQuery" class="form-label fw-bold">
                                        <i class="fas fa-search me-2"></i>Tìm kiếm bảo hành
                                    </label>
                                    <input 
                                        type="text" 
                                        class="form-control form-control-lg" 
                                        id="searchQuery" 
                                        name="query" 
                                        placeholder="{{ trans('warranty::app.frontend.search.search-placeholder') }}"
                                        required
                                        minlength="3"
                                    >
                                    <div class="form-text">
                                        Nhập ít nhất 3 ký tự để tìm kiếm
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-search me-2"></i>
                                    {{ trans('warranty::app.frontend.search.search-btn') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="text-center d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Đang tìm kiếm...</span>
                </div>
                <p class="mt-2">Đang tìm kiếm bảo hành...</p>
            </div>

            <!-- Search Results -->
            <div id="searchResults"></div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('warrantySearchForm');
            const resultsDiv = document.getElementById('searchResults');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const searchInput = document.getElementById('searchQuery');

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const query = searchInput.value.trim();
                if (query.length < 3) {
                    alert('Vui lòng nhập ít nhất 3 ký tự');
                    return;
                }

                searchWarranties(query);
            });

            function searchWarranties(query) {
                // Show loading
                loadingSpinner.classList.remove('d-none');
                resultsDiv.innerHTML = '';

                // Make API request
                fetch('{{ route("warranty.search.search") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ query: query })
                })
                .then(response => response.json())
                .then(data => {
                    loadingSpinner.classList.add('d-none');
                    
                    if (data.success && data.warranties.length > 0) {
                        displayResults(data.warranties);
                    } else {
                        displayNoResults();
                    }
                })
                .catch(error => {
                    loadingSpinner.classList.add('d-none');
                    console.error('Error:', error);
                    displayError();
                });
            }

            function displayResults(warranties) {
                let html = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Tìm thấy ${warranties.length} kết quả bảo hành
                    </div>
                `;

                warranties.forEach(warranty => {
                    const statusClass = getStatusClass(warranty.status);
                    const remainingInfo = warranty.is_active 
                        ? `<span class="text-success">(Còn ${warranty.remaining_days} ngày)</span>`
                        : warranty.is_expired 
                            ? '<span class="text-danger">(Đã hết hạn)</span>'
                            : '';

                    html += `
                        <div class="warranty-card">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="card-title">
                                        <i class="fas fa-certificate me-2 text-primary"></i>
                                        ${warranty.warranty_number}
                                    </h5>
                                    <div class="mb-2">
                                        <span class="warranty-status ${statusClass}">
                                            ${warranty.status_text}
                                        </span>
                                    </div>
                                    <p class="mb-1">
                                        <strong>Sản phẩm:</strong> ${warranty.product_name}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Serial:</strong> 
                                        <span class="badge bg-dark">${warranty.product_serial}</span>
                                    </p>
                                    <p class="mb-1">
                                        <strong>Khách hàng:</strong> ${warranty.customer_name}
                                    </p>
                                    <p class="mb-0">
                                        <strong>Điện thoại:</strong> 
                                        <span class="badge bg-info">${warranty.customer_phone}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <strong>Gói bảo hành:</strong> 
                                        <span class="badge bg-secondary">${warranty.package_name}</span>
                                    </div>
                                    <p class="mb-1">
                                        <strong>Ngày mua:</strong> ${warranty.purchase_date}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Bảo hành từ:</strong> ${warranty.start_date}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Bảo hành đến:</strong> 
                                        ${warranty.end_date} ${remainingInfo}
                                    </p>
                                </div>
                            </div>
                        </div>
                    `;
                });

                resultsDiv.innerHTML = html;
            }

            function displayNoResults() {
                resultsDiv.innerHTML = `
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ trans('warranty::app.frontend.search.no-results') }}
                    </div>
                    <div class="text-center">
                        <p class="text-muted">
                            Vui lòng kiểm tra lại thông tin tìm kiếm hoặc liên hệ với chúng tôi để được hỗ trợ.
                        </p>
                    </div>
                `;
            }

            function displayError() {
                resultsDiv.innerHTML = `
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Có lỗi xảy ra khi tìm kiếm. Vui lòng thử lại sau.
                    </div>
                `;
            }

            function getStatusClass(status) {
                switch(status) {
                    case 'active': return 'status-active';
                    case 'expired': return 'status-expired';
                    case 'claimed': return 'status-claimed';
                    case 'cancelled': return 'status-cancelled';
                    default: return 'status-active';
                }
            }
        });
    </script>
</body>
</html>