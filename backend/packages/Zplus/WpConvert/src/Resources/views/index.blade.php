<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('wp_convert::app.title') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            transition: border-color 0.3s ease;
        }
        .upload-area.dragover {
            border-color: #0d6efd;
            background-color: #f8f9fa;
        }
        .stats-card {
            border-left: 4px solid #0d6efd;
        }
        .progress {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">{{ __('wp_convert::app.title') }}</h4>
                        <p class="text-muted mb-0">{{ __('wp_convert::app.description') }}</p>
                    </div>
                    <div class="card-body">
                        <!-- Upload Form -->
                        <form id="convertForm" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- File Upload Area -->
                            <div class="upload-area mb-4" id="uploadArea">
                                <i class="bi bi-cloud-upload fs-1 text-muted mb-3"></i>
                                <h5>{{ __('wp_convert::app.upload.title') }}</h5>
                                <p class="text-muted">{{ __('wp_convert::app.upload.description') }}</p>
                                <input type="file" 
                                       id="woocommerce_csv" 
                                       name="woocommerce_csv" 
                                       accept=".csv,.txt" 
                                       class="form-control d-none">
                                <button type="button" 
                                        class="btn btn-outline-primary" 
                                        onclick="document.getElementById('woocommerce_csv').click()">
                                    {{ __('wp_convert::app.upload.select_file') }}
                                </button>
                            </div>
                            
                            <!-- File Info -->
                            <div id="fileInfo" class="alert alert-info d-none">
                                <strong>{{ __('wp_convert::app.selected_file') }}:</strong> 
                                <span id="fileName"></span>
                                <button type="button" class="btn-close float-end" onclick="clearFile()"></button>
                            </div>
                            
                            <!-- Stats Display -->
                            <div id="statsDisplay" class="row d-none mb-4">
                                <div class="col-md-4">
                                    <div class="card stats-card">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ __('wp_convert::app.stats.total_rows') }}</h6>
                                            <h4 class="text-primary" id="totalRows">0</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card stats-card">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ __('wp_convert::app.stats.convertible') }}</h6>
                                            <h4 class="text-success" id="convertibleProducts">0</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card stats-card">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ __('wp_convert::app.stats.conversion_rate') }}</h6>
                                            <h4 class="text-info" id="conversionRate">0%</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Selection -->
                            <div id="actionSection" class="d-none">
                                <h6>{{ __('wp_convert::app.action.title') }}</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <i class="bi bi-download fs-1 text-primary mb-3"></i>
                                                <h6>{{ __('wp_convert::app.action.download.title') }}</h6>
                                                <p class="text-muted">{{ __('wp_convert::app.action.download.description') }}</p>
                                                <button type="button" 
                                                        class="btn btn-primary" 
                                                        onclick="performAction('download')">
                                                    {{ __('wp_convert::app.action.download.button') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <i class="bi bi-database-add fs-1 text-success mb-3"></i>
                                                <h6>{{ __('wp_convert::app.action.import.title') }}</h6>
                                                <p class="text-muted">{{ __('wp_convert::app.action.import.description') }}</p>
                                                <button type="button" 
                                                        class="btn btn-success" 
                                                        onclick="performAction('import')">
                                                    {{ __('wp_convert::app.action.import.button') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="progress mb-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" 
                                     style="width: 0%"></div>
                            </div>
                            
                            <!-- Results -->
                            <div id="results" class="d-none"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    
    <script>
        // CSRF token setup
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // File upload handling
        document.getElementById('woocommerce_csv').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                handleFileSelect(file);
            }
        });

        // Drag and drop handling
        const uploadArea = document.getElementById('uploadArea');
        
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        
        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
        });
        
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('woocommerce_csv').files = files;
                handleFileSelect(files[0]);
            }
        });

        function handleFileSelect(file) {
            // Validate file type
            if (!file.name.endsWith('.csv') && !file.name.endsWith('.txt')) {
                showAlert('error', 'Please select a CSV file.');
                return;
            }
            
            // Show file info
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileInfo').classList.remove('d-none');
            
            // Get file stats
            getFileStats();
        }

        function clearFile() {
            document.getElementById('woocommerce_csv').value = '';
            document.getElementById('fileInfo').classList.add('d-none');
            document.getElementById('statsDisplay').classList.add('d-none');
            document.getElementById('actionSection').classList.add('d-none');
        }

        function getFileStats() {
            const fileInput = document.getElementById('woocommerce_csv');
            if (!fileInput.files[0]) return;
            
            const formData = new FormData();
            formData.append('woocommerce_csv', fileInput.files[0]);
            formData.append('_token', token);
            
            showProgress(true);
            
            fetch('{{ route("wp-convert.stats") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showProgress(false);
                
                if (data.success) {
                    // Display stats
                    document.getElementById('totalRows').textContent = data.stats.total_rows;
                    document.getElementById('convertibleProducts').textContent = data.stats.convertible_products;
                    document.getElementById('conversionRate').textContent = data.stats.conversion_rate + '%';
                    
                    document.getElementById('statsDisplay').classList.remove('d-none');
                    document.getElementById('actionSection').classList.remove('d-none');
                } else {
                    showAlert('error', data.message || 'Error analyzing file');
                }
            })
            .catch(error => {
                showProgress(false);
                showAlert('error', 'Error analyzing file: ' + error.message);
            });
        }

        function performAction(action) {
            const fileInput = document.getElementById('woocommerce_csv');
            if (!fileInput.files[0]) {
                showAlert('error', 'Please select a file first.');
                return;
            }
            
            const formData = new FormData();
            formData.append('woocommerce_csv', fileInput.files[0]);
            formData.append('action', action);
            formData.append('_token', token);
            
            showProgress(true);
            
            fetch('{{ route("wp-convert.convert") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showProgress(false);
                
                if (data.success) {
                    if (action === 'download' && data.download_url) {
                        showAlert('success', data.message + ` (${data.converted_count} products converted)`);
                        // Auto-download the file
                        window.location.href = data.download_url;
                    } else {
                        showAlert('success', data.message + ` (${data.converted_count} products converted, ${data.imported_count} imported)`);
                    }
                } else {
                    showAlert('error', data.message || 'Conversion failed');
                }
            })
            .catch(error => {
                showProgress(false);
                showAlert('error', 'Error during conversion: ' + error.message);
            });
        }

        function showProgress(show) {
            const progress = document.querySelector('.progress');
            if (show) {
                progress.style.display = 'block';
            } else {
                progress.style.display = 'none';
            }
        }

        function showAlert(type, message) {
            const alertClass = type === 'error' ? 'alert-danger' : 'alert-success';
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            const results = document.getElementById('results');
            results.innerHTML = alertHtml;
            results.classList.remove('d-none');
            
            // Auto-hide success messages after 5 seconds
            if (type === 'success') {
                setTimeout(() => {
                    const alert = results.querySelector('.alert');
                    if (alert) {
                        alert.classList.remove('show');
                    }
                }, 5000);
            }
        }
    </script>
</body>
</html>