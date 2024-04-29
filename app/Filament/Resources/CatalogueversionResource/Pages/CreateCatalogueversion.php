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

        //Read CSV
        try {
            $csvFile = $data['csv_file']->get();
            $rows = explode("\n", $csvFile); // Split the string into lines
            $headers = str_getcsv(array_shift($rows));
            $data = [];


        } catch (\Exception $e) {
            Log::error("Failed reading csv file");
            throw $e;
        }
        // Format CSV into array
        try {
            foreach ($rows as $row) {
                $values = str_getcsv($row, ",");

                if (count($values) === count($headers)) {
                    $rowData = array_combine($headers, $values);
                    $data[] = $rowData;
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed formatting data");
            throw $e;
        }

        set_time_limit(300);
        // Insert data to Catalogue table
        try {

            foreach ($data as $catalogueItem) {
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
                    'cover_image' => '',
                    'created_at' => now(), // Assuming you want to set creation timestamps
                    'updated_at' => now(), // Assuming you want to set update timestamps
                ];
            }
            DB::table('catalogues')->insert($catalogueItems);


        } catch (\Exception $e) {
            Log::error('failed inserting into database');
        }


        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
