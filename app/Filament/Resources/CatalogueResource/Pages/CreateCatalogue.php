<?php

namespace App\Filament\Resources\CatalogueResource\Pages;

use App\Filament\Resources\CatalogueResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateCatalogue extends CreateRecord
{
    protected static string $resource = CatalogueResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        DB::beginTransaction();

        try {
            $record = parent::handleRecordCreation($data);
            $currentVersion = $record['version'];

            // Read CSV
            $csvFile = $data['csv_file']->get();
            $rows = explode("\n", $csvFile);
            $headers = str_getcsv(array_shift($rows));

            $catalogueItems = [];

            // Format CSV into array
            foreach ($rows as $row) {
                $values = str_getcsv($row, ",");
                if (count($values) === count($headers)) {
                    $rowData = array_combine($headers, $values);
                    $catalogueItems[] = $rowData;
                }
            }

            // Insert data into Catalogue table
            foreach ($catalogueItems as $catalogueItem) {
                $newRecords[] = [
                    'unique_reference' => $catalogueItem['Unique Reference'] ?? '',
                    'version_id' => $currentVersion,
                    'type' => $catalogueItem['Type'] ?? '',
                    'brand' => $catalogueItem['Brand'] ?? '',
                    'name' => $catalogueItem['Name'] ?? '',
                    'vendor' => $catalogueItem['Vendor'] ?? '',
                    'category' => $catalogueItem['Category'] ?? '',
                    'price_inc_gst' => $catalogueItem['Price inc gst'] ?? '',
                    'processor' => $catalogueItem['Processor'] ?? '',
                    'storage' => $catalogueItem['Storage'] ?? '',
                    'memory' => $catalogueItem['Memory'] ?? '',
                    'form_factor' => $catalogueItem['Form Factor'] ?? '',
                    'display' => $catalogueItem['Display'] ?? '',
                    'graphics' => $catalogueItem['Graphics'] ?? '',
                    'wireless' => $catalogueItem['Wireless'] ?? '',
                    'webcam' => $catalogueItem['Webcam'] ?? '',
                    'operating_system' => $catalogueItem['Operating System'] ?? '',
                    'warranty' => $catalogueItem['Warranty'] ?? '',
                    'battery_life' => $catalogueItem['Battery Life'] ?? '',
                    'weight' => $catalogueItem['Weight'] ?? '',
                    'stylus' => $catalogueItem['Stylus'] ?? '',
                    'other' => $catalogueItem['Other'] ?? '',
                    'available_now' => $catalogueItem['Available now'] ?? '',
                    'corporate' => $catalogueItem['Corporate'] ?? '',
                    'administration' => $catalogueItem['Administration'] ?? '',
                    'curriculum' => $catalogueItem['Curriculum'] ?? '',
                    'image' => $catalogueItem['Image'] ?? '',
                    'product_number' => $catalogueItem['Product Number'] ?? '',
                    'price_expiry' => $catalogueItem['Price Expiry'] ?? '',
                    'cover_image' => '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (!empty($newRecords)) {
                DB::table('catalogues')->insert($newRecords);
            }

            DB::commit();

            return $record;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to handle record creation: " . $e->getMessage());
            throw $e;
        }
    }

}
