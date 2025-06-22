<?php

namespace Zplus\WpConvert\Helpers;

class WooCommerceToBagistoConverter
{
    /**
     * Field mapping from WooCommerce to Bagisto
     */
    protected array $fieldMapping = [
        'ID' => 'sku', // Use ID as SKU since WooCommerce might not have unique SKUs
        'post_title' => 'name',
        'post_content' => 'description',
        'post_excerpt' => 'short_description',
        'post_status' => 'status',
        '_regular_price' => 'price',
        '_sale_price' => 'special_price',
        '_weight' => 'weight',
        '_length' => 'length',
        '_width' => 'width',
        '_height' => 'height',
        '_sku' => 'original_sku',
        'post_name' => 'url_key',
        '_stock_quantity' => 'quantity',
        '_manage_stock' => 'manage_stock',
        '_stock_status' => 'inventory_source_qty',
        'product_cat' => 'categories',
        'images' => 'images',
    ];

    /**
     * Required Bagisto fields with default values
     */
    protected array $requiredFields = [
        'type' => 'simple',
        'attribute_family_code' => 'default',
        'channel' => 'default',
        'locale' => 'en',
        'tax_category_id' => null,
        'new' => 1,
        'featured' => 0,
        'visible_individually' => 1,
        'guest_checkout' => 1,
        'inventories' => 'default=0',
    ];

    /**
     * Convert WooCommerce CSV data to Bagisto format
     *
     * @param array $wooCommerceData
     * @return array
     */
    public function convert(array $wooCommerceData): array
    {
        $bagistoData = [];

        foreach ($wooCommerceData as $row) {
            $convertedRow = $this->convertRow($row);
            if ($convertedRow) {
                $bagistoData[] = $convertedRow;
            }
        }

        return $bagistoData;
    }

    /**
     * Convert a single row from WooCommerce to Bagisto format
     *
     * @param array $row
     * @return array|null
     */
    protected function convertRow(array $row): ?array
    {
        $converted = [];

        // Map fields from WooCommerce to Bagisto
        foreach ($this->fieldMapping as $wooField => $bagistoField) {
            if (isset($row[$wooField])) {
                $converted[$bagistoField] = $this->transformValue($wooField, $row[$wooField]);
            }
        }

        // Add required fields with defaults
        foreach ($this->requiredFields as $field => $defaultValue) {
            if (!isset($converted[$field])) {
                $converted[$field] = $defaultValue;
            }
        }

        // Handle special conversions
        $converted = $this->handleSpecialConversions($converted, $row);

        // Validate required fields
        if (!$this->validateRequiredFields($converted)) {
            return null;
        }

        return $converted;
    }

    /**
     * Transform value based on field type
     *
     * @param string $field
     * @param mixed $value
     * @return mixed
     */
    protected function transformValue(string $field, $value)
    {
        switch ($field) {
            case 'post_status':
                return $value === 'publish' ? 1 : 0;
            
            case '_regular_price':
            case '_sale_price':
            case '_weight':
            case '_length':
            case '_width':
            case '_height':
                return is_numeric($value) ? (float)$value : 0;
            
            case '_stock_quantity':
                return is_numeric($value) ? (int)$value : 0;
            
            case '_manage_stock':
                return $value === 'yes' ? 1 : 0;
            
            case '_stock_status':
                return $value === 'instock' ? 1 : 0;
            
            case 'post_name':
                // Generate URL key from post name or title
                return $this->generateUrlKey($value);
            
            case 'product_cat':
                // Convert categories (comma-separated to pipe-separated)
                return str_replace(',', '|', $value);
            
            default:
                return $value;
        }
    }

    /**
     * Handle special field conversions
     *
     * @param array $converted
     * @param array $originalRow
     * @return array
     */
    protected function handleSpecialConversions(array $converted, array $originalRow): array
    {
        // Generate SKU if not present
        if (empty($converted['sku'])) {
            $converted['sku'] = 'wp-product-' . ($originalRow['ID'] ?? uniqid());
        }

        // Generate URL key if not present
        if (empty($converted['url_key'])) {
            $converted['url_key'] = $this->generateUrlKey($converted['name'] ?? $converted['sku']);
        }

        // Handle inventory
        if (isset($converted['quantity'])) {
            $converted['inventories'] = 'default=' . $converted['quantity'];
            unset($converted['quantity']);
        }

        // Handle special price dates if sale price is set
        if (!empty($converted['special_price']) && $converted['special_price'] > 0) {
            $converted['special_price_from'] = date('Y-m-d');
            $converted['special_price_to'] = date('Y-m-d', strtotime('+1 year'));
        }

        // Clean up empty values
        $converted = array_filter($converted, function($value) {
            return $value !== null && $value !== '';
        });

        return $converted;
    }

    /**
     * Generate URL key from string
     *
     * @param string $string
     * @return string
     */
    protected function generateUrlKey(string $string): string
    {
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9-]/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        return trim($string, '-');
    }

    /**
     * Validate required fields
     *
     * @param array $data
     * @return bool
     */
    protected function validateRequiredFields(array $data): bool
    {
        $required = ['sku', 'name', 'type', 'attribute_family_code'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get Bagisto CSV headers
     *
     * @return array
     */
    public function getBagistoHeaders(): array
    {
        return [
            'sku',
            'type',
            'name',
            'description',
            'short_description',
            'url_key',
            'price',
            'special_price',
            'special_price_from',
            'special_price_to',
            'weight',
            'length',
            'width',
            'height',
            'status',
            'tax_category_id',
            'attribute_family_code',
            'channel',
            'locale',
            'categories',
            'inventories',
            'images',
            'new',
            'featured',
            'visible_individually',
            'guest_checkout'
        ];
    }
}