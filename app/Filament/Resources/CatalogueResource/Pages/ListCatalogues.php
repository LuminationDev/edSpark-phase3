<?php

namespace App\Filament\Resources\CatalogueResource\Pages;

use App\Filament\Imports\CatalogueUpdater;
use App\Filament\Resources\CatalogueResource;
use App\Models\Catalogue;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;
use App\Models\Catalogueversion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Outerweb\ImageLibrary\Models\Image;

class ListCatalogues extends ListRecords
{
    protected static string $resource = CatalogueResource::class;
    protected ?string $subheading;

    public function __construct()
    {
        $this->subheading = 'Catalogue version: ' . strval(Catalogueversion::getActiveCatalogueId());
    }

    private function findExistingImageId($titles)
    {
        return Image::whereIn('title', $titles)->pluck('id')->first() ?? '';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Delete all')
                ->icon('heroicon-m-x-mark')
                ->color(Color::Rose)
                ->requiresConfirmation()
                ->action(function () {
                    Catalogue::deleteAll();
                }),
            Action::make("Bulk update via CSV")
                ->label('Bulk update via CSV')
                ->form([
                    FileUpload::make('csv_file')
                        ->label('CSV file')
                        ->acceptedFileTypes(['text/csv'])
                        ->storeFiles(false)
                        ->required(),
                ])
                ->action(function (array $data, $record): void {

                    DB::beginTransaction();
                    $updatedCount = 0;
                    $createdCount = 0;
                    try {
                        $activeCatalogueId = Catalogueversion::getActiveCatalogueId();

                        // Read CSV
                        $csvFile = $data['csv_file']->get();
                        $rows = explode("\n", $csvFile); // Split the string into lines
                        $headers = str_getcsv(array_shift($rows));

                        // Format CSV into array
                        $catalogueItems = [];
                        foreach ($rows as $row) {
                            $values = str_getcsv($row, ",");
                            if (count($values) === count($headers)) {
                                $rowData = array_combine($headers, $values);
                                $catalogueItems[] = $rowData;
                            }
                        }

                        foreach ($catalogueItems as $catalogueItem) {
                            // Check if the record exists based on unique reference
                            $existingRecord = DB::table('catalogues')
                                ->where('unique_reference', $catalogueItem['Unique Reference'])
                                ->where('version_id', $activeCatalogueId)
                                ->first();

                            $uniqueReference = $catalogueItem['Unique Reference'];
                            $extensions = ['png', 'jpg'];
                            $modifiedTitles = array_map(function ($extension) use ($uniqueReference) {
                                return strtolower($uniqueReference) . '.' . $extension;
                            }, $extensions);

                            $existingImageId = $this->findExistingImageId($modifiedTitles);

                            if (!$existingImageId) {
                                $existingImageId = $this->findExistingImageId([strtolower($catalogueItem['Image'])]);
                            }


                            if ($existingRecord) {
                                $updateRecords[] = [
                                    'id' => $existingRecord->id,
                                    'data' => [
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
                                        'cover_image' => $existingImageId,
                                        'updated_at' => now(),
                                    ]
                                ];
                                $updatedCount++;
                            } else {
                                // Insert new record if not found
                                $newRecords[] = [
                                    'unique_reference' => $catalogueItem['Unique Reference'] ?? '',
                                    'version_id' => $activeCatalogueId,
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
                                    'cover_image' => $existingImageId,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                                $createdCount++;
                            }
                        }

                        // Bulk update
                        if (!empty($updateRecords)) {
                            foreach ($updateRecords as $record) {
                                DB::table('catalogues')
                                    ->where('id', $record['id'])
                                    ->update($record['data']);
                            }

                        }

                        // Bulk insert - with batching to resolve too long error
                        if (!empty($newRecords)) {
                            $batchSize = 300;
                            $tempBatch = [];
                            if (count($newRecords) > $batchSize) {
                                foreach ($newRecords as $newRecord) {
                                    $tempBatch[] = $newRecord;
                                    if (count($tempBatch) >= $batchSize) {
                                        DB::table('catalogues')->insert($tempBatch);
                                        $tempBatch = [];
                                    }
                                }
                                // do the rest
                                if (!empty($tempBatch)) {
                                    DB::table('catalogues')->insert($tempBatch);
                                }

                            } else {
                                DB::table('catalogues')->insert($newRecords);
                            }
                        }

                        DB::commit();
                        Log::info('Created ' . strval($createdCount) . ' records');
                        Log::info('Updated ' . strval($updatedCount) . ' records');
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error("Failed bulk update: " . $e->getMessage());
                        throw $e;
                    }
                })

        ];
    }

}
