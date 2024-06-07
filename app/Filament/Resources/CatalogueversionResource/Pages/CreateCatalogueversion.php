<?php

namespace App\Filament\Resources\CatalogueversionResource\Pages;

use App\Filament\Resources\CatalogueversionResource;
use App\Models\Catalogue;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Outerweb\ImageLibrary\Models\Image;

class CreateCatalogueversion extends CreateRecord
{
    protected static string $resource = CatalogueversionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['is_active'] = false;
        return parent::mutateFormDataBeforeCreate($data);
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Create the main record.
        $record = parent::handleRecordCreation($data);
        $currentVersion = $record['version'];
        $batchSize = 50; // Define your batch size here

        // Read CSV
        try {
            $csvFile = $data['csv_file']->get();
            $rows = explode("\n", $csvFile); // Split the string into lines
            $headers = str_getcsv(array_shift($rows));
        } catch (\Exception $e) {
            Log::error("Failed reading csv file");
            throw $e;
        }

        set_time_limit(300);

        // Initialize variables for batch processing
        $dataBatch = [];
        $batchCount = 0;

        DB::beginTransaction();

        try {
            foreach ($rows as $row) {
                $values = str_getcsv($row, ",");
                if (count($values) === count($headers)) {
                    $rowData = array_combine($headers, $values);
                    $dataBatch[] = $rowData;

                    if (count($dataBatch) >= $batchSize) {
                        $this->processBatch($dataBatch, $currentVersion);
                        $dataBatch = []; // Reset batch
                        $batchCount++;
                    }
                }
            }

            // Process any remaining rows
            if (!empty($dataBatch)) {
                $this->processBatch($dataBatch, $currentVersion);
                $batchCount++;
            }

            DB::commit(); // Commit transaction if all batches processed successfully
        } catch (\Exception $e) {
            Log::error("Failed formatting or processing data in batch: " . $e->getMessage());
            // Rollback if an error occurs
            DB::rollBack();
            throw $e;
        }

        Log::info("Processed $batchCount batches successfully.");
        return $record;
    }


    private function findExistingImageId($titles) {
        return Image::whereIn('title', $titles)->pluck('id')->first() ?? '';
    }

    private function processBatch(array $dataBatch, $currentVersion)
    {
        $catalogueItems = [];

        foreach ($dataBatch as $catalogueItem) {
            $uniqueReference = $catalogueItem['Unique Reference'];
            $extensions = ['png', 'jpg'];
            $modifiedTitles = array_map(function ($extension) use ($uniqueReference) {
                return strtolower($uniqueReference) . '.' . $extension;
            }, $extensions);

            $existingImageId = $this->findExistingImageId($modifiedTitles);

            if (!$existingImageId) {
                $existingImageId = $this->findExistingImageId([strtolower($catalogueItem['Image'])]);
            }

            $catalogueItems[] = [
                'unique_reference' => $catalogueItem['Unique Reference'],
                'version_id' => $currentVersion,
                'type' => $catalogueItem['Type'],
                'brand' => $catalogueItem['Brand'],
                'name' => $catalogueItem['Name'],
                'vendor' => $catalogueItem['Vendor'],
                'category' => $catalogueItem['Category'],
                'price_inc_gst' => $catalogueItem['Price inc gst'],
                'processor' => $catalogueItem['Processor'],
                'storage' => $catalogueItem['Storage'],
                'memory' => $catalogueItem['Memory'],
                'form_factor' => $catalogueItem['Form Factor'],
                'display' => $catalogueItem['Display'],
                'graphics' => $catalogueItem['Graphics'],
                'wireless' => $catalogueItem['Wireless'],
                'webcam' => $catalogueItem['Webcam'],
                'operating_system' => $catalogueItem['Operating System'],
                'warranty' => $catalogueItem['Warranty'],
                'battery_life' => $catalogueItem['Battery Life'],
                'weight' => $catalogueItem['Weight'],
                'stylus' => $catalogueItem['Stylus'],
                'other' => $catalogueItem['Other'],
                'available_now' => $catalogueItem['Available now'],
                'corporate' => $catalogueItem['Corporate'],
                'administration' => $catalogueItem['Administration'],
                'curriculum' => $catalogueItem['Curriculum'],
                'image' => $catalogueItem['Image'],
                'product_number' => $catalogueItem['Product Number'],
                'price_expiry' => $catalogueItem['Price Expiry'],
                'cover_image' => $existingImageId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('catalogues')->insert($catalogueItems);
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
