<?php

namespace Zplus\WpConvert\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Zplus\WpConvert\Helpers\WooCommerceToBagistoConverter;

class WpConvertController
{
    protected WooCommerceToBagistoConverter $converter;

    public function __construct(WooCommerceToBagistoConverter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * Show the conversion interface
     */
    public function index()
    {
        return view('wp_convert::index');
    }

    /**
     * Upload and convert WooCommerce CSV
     */
    public function convert(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'woocommerce_csv' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
            'action' => 'required|in:download,import'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('woocommerce_csv');
            $action = $request->input('action');

            // Read and parse WooCommerce CSV
            $wooCommerceData = $this->parseCSV($file);
            
            if (empty($wooCommerceData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid data found in the CSV file'
                ], 400);
            }

            // Convert to Bagisto format
            $bagistoData = $this->converter->convert($wooCommerceData);

            if (empty($bagistoData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid products could be converted'
                ], 400);
            }

            if ($action === 'download') {
                // Generate CSV for download
                $filename = $this->generateBagistoCSV($bagistoData);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Conversion completed successfully',
                    'download_url' => route('wp-convert.download', ['filename' => basename($filename)]),
                    'converted_count' => count($bagistoData)
                ]);
            } else {
                // Import directly to Bagisto
                $result = $this->importToBagisto($bagistoData);
                
                return response()->json([
                    'success' => $result['success'],
                    'message' => $result['message'],
                    'converted_count' => count($bagistoData),
                    'imported_count' => $result['imported_count'] ?? 0
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during conversion: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download converted CSV file
     */
    public function download(string $filename): StreamedResponse
    {
        $path = 'wp-convert/' . $filename;
        
        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('local')->download($path, 'bagisto-products-' . date('Y-m-d-H-i-s') . '.csv');
    }

    /**
     * Parse CSV file to array
     */
    protected function parseCSV($file): array
    {
        $data = [];
        $headers = [];
        
        if (($handle = fopen($file->getPathname(), 'r')) !== false) {
            $lineNumber = 0;
            
            while (($row = fgetcsv($handle, 0, ',')) !== false) {
                if ($lineNumber === 0) {
                    // First row contains headers
                    $headers = array_map('trim', $row);
                } else {
                    // Map row data to headers
                    if (count($row) === count($headers)) {
                        $data[] = array_combine($headers, array_map('trim', $row));
                    }
                }
                $lineNumber++;
            }
            
            fclose($handle);
        }

        return $data;
    }

    /**
     * Generate Bagisto CSV file
     */
    protected function generateBagistoCSV(array $data): string
    {
        $filename = 'wp-convert/bagisto-products-' . uniqid() . '.csv';
        $path = storage_path('app/' . $filename);
        
        // Ensure directory exists
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $handle = fopen($path, 'w');
        
        // Write headers
        $headers = $this->converter->getBagistoHeaders();
        fputcsv($handle, $headers);
        
        // Write data
        foreach ($data as $row) {
            $orderedRow = [];
            foreach ($headers as $header) {
                $orderedRow[] = $row[$header] ?? '';
            }
            fputcsv($handle, $orderedRow);
        }
        
        fclose($handle);
        
        return $filename;
    }

    /**
     * Import data directly to Bagisto
     */
    protected function importToBagisto(array $data): array
    {
        try {
            // Generate temporary CSV file for import
            $filename = $this->generateBagistoCSV($data);
            $filePath = storage_path('app/' . $filename);

            // Create an import record using Bagisto's DataTransfer system
            $importRepository = app(\Webkul\DataTransfer\Repositories\ImportRepository::class);
            
            $import = $importRepository->create([
                'type' => 'products',
                'action' => 'create',
                'validation_strategy' => 'skip-errors',
                'allowed_errors' => 10,
                'file_path' => $filename,
                'images_directory_path' => '',
                'processed_rows_count' => 0,
                'invalid_rows_count' => 0,
                'errors_count' => 0,
                'errors' => '{}',
                'field_separator' => ',',
                'state' => 'pending',
            ]);

            // Use Bagisto's Import helper to process the file
            $importHelper = new \Webkul\DataTransfer\Helpers\Import();
            $importHelper->setImport($import);
            
            // Start the import process
            $result = $importHelper->start();

            // Clean up temporary file
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Products imported successfully',
                    'imported_count' => count($data)
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Import failed during processing',
                    'imported_count' => 0
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
                'imported_count' => 0
            ];
        }
    }

    /**
     * Get conversion statistics
     */
    public function stats(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'woocommerce_csv' => 'required|file|mimes:csv,txt|max:10240'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('woocommerce_csv');
            $wooCommerceData = $this->parseCSV($file);
            $bagistoData = $this->converter->convert($wooCommerceData);

            return response()->json([
                'success' => true,
                'stats' => [
                    'total_rows' => count($wooCommerceData),
                    'convertible_products' => count($bagistoData),
                    'conversion_rate' => count($wooCommerceData) > 0 ? 
                        round((count($bagistoData) / count($wooCommerceData)) * 100, 2) : 0
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error analyzing file: ' . $e->getMessage()
            ], 500);
        }
    }
}