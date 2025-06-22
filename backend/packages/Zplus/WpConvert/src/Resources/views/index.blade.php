<x-admin::layouts>
    <x-slot:title>
        {{ __('wp_convert::app.title') }}
    </x-slot:title>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                {{ __('wp_convert::app.title') }}
            </p>
        </div>

        <div class="flex flex-col gap-4">
            <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    {{ __('wp_convert::app.description') }}
                </p>

                <!-- Upload Form -->
                <form id="convertForm" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- File Upload Area -->
                    <div class="upload-area border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center mb-6 transition-colors" id="uploadArea">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>
                            <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
                                {{ __('wp_convert::app.upload.title') }}
                            </h5>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">
                                {{ __('wp_convert::app.upload.description') }}
                            </p>
                            <input type="file" 
                                   id="woocommerce_csv" 
                                   name="woocommerce_csv" 
                                   accept=".csv,.txt" 
                                   class="hidden">
                            <button type="button" 
                                    class="secondary-button" 
                                    onclick="document.getElementById('woocommerce_csv').click()">
                                {{ __('wp_convert::app.upload.select_file') }}
                            </button>
                        </div>
                    </div>
                    
                    <!-- File Info -->
                    <div id="fileInfo" class="hidden bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <strong class="text-blue-800 dark:text-blue-200">{{ __('wp_convert::app.selected_file') }}:</strong> 
                                <span id="fileName" class="text-blue-700 dark:text-blue-300"></span>
                            </div>
                            <button type="button" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200" onclick="clearFile()">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Stats Display -->
                    <div id="statsDisplay" class="hidden grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-white dark:bg-gray-800 border border-l-4 border-l-blue-500 rounded-lg p-4">
                            <h6 class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                {{ __('wp_convert::app.stats.total_rows') }}
                            </h6>
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="totalRows">0</div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 border border-l-4 border-l-green-500 rounded-lg p-4">
                            <h6 class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                {{ __('wp_convert::app.stats.convertible') }}
                            </h6>
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400" id="convertibleProducts">0</div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 border border-l-4 border-l-purple-500 rounded-lg p-4">
                            <h6 class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                {{ __('wp_convert::app.stats.conversion_rate') }}
                            </h6>
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400" id="conversionRate">0%</div>
                        </div>
                    </div>
                    
                    <!-- Action Selection -->
                    <div id="actionSection" class="hidden">
                        <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                            {{ __('wp_convert::app.action.title') }}
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 text-center">
                                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
                                    {{ __('wp_convert::app.action.download.title') }}
                                </h6>
                                <p class="text-gray-600 dark:text-gray-300 mb-4">
                                    {{ __('wp_convert::app.action.download.description') }}
                                </p>
                                <button type="button" 
                                        class="primary-button w-full" 
                                        onclick="performAction('download')">
                                    {{ __('wp_convert::app.action.download.button') }}
                                </button>
                            </div>
                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 text-center">
                                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                    </svg>
                                </div>
                                <h6 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
                                    {{ __('wp_convert::app.action.import.title') }}
                                </h6>
                                <p class="text-gray-600 dark:text-gray-300 mb-4">
                                    {{ __('wp_convert::app.action.import.description') }}
                                </p>
                                <button type="button" 
                                        class="primary-button w-full" 
                                        onclick="performAction('import')">
                                    {{ __('wp_convert::app.action.import.button') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="hidden mt-6" id="progressBar">
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full animate-pulse" style="width: 100%"></div>
                        </div>
                        <p class="text-center text-gray-600 dark:text-gray-300 mt-2">Processing...</p>
                    </div>
                    
                    <!-- Results -->
                    <div id="results" class="hidden mt-6"></div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .upload-area.dragover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        .dark .upload-area.dragover {
            background-color: #1e3a8a;
        }
    </style>

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
            document.getElementById('fileInfo').classList.remove('hidden');
            
            // Get file stats
            getFileStats();
        }

        function clearFile() {
            document.getElementById('woocommerce_csv').value = '';
            document.getElementById('fileInfo').classList.add('hidden');
            document.getElementById('statsDisplay').classList.add('hidden');
            document.getElementById('actionSection').classList.add('hidden');
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
                    
                    document.getElementById('statsDisplay').classList.remove('hidden');
                    document.getElementById('actionSection').classList.remove('hidden');
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
            const progress = document.getElementById('progressBar');
            if (show) {
                progress.classList.remove('hidden');
            } else {
                progress.classList.add('hidden');
            }
        }

        function showAlert(type, message) {
            const alertClass = type === 'error' ? 'bg-red-50 border-red-200 text-red-800 dark:bg-red-900/20 dark:border-red-800 dark:text-red-200' : 'bg-green-50 border-green-200 text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-200';
            const iconColor = type === 'error' ? 'text-red-500' : 'text-green-500';
            const icon = type === 'error' ? 
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />' : 
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
            
            const alertHtml = `
                <div class="border rounded-lg p-4 ${alertClass}">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 ${iconColor} mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${icon}
                        </svg>
                        <div class="flex-1">
                            ${message}
                        </div>
                        <button type="button" class="ml-3 ${iconColor} hover:opacity-70" onclick="this.parentElement.parentElement.remove()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            
            const results = document.getElementById('results');
            results.innerHTML = alertHtml;
            results.classList.remove('hidden');
            
            // Auto-hide success messages after 8 seconds
            if (type === 'success') {
                setTimeout(() => {
                    const alert = results.querySelector('div');
                    if (alert) {
                        alert.remove();
                        results.classList.add('hidden');
                    }
                }, 8000);
            }
        }
    </script>
</x-admin::layouts>