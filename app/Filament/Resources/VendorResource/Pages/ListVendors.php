<?php

namespace App\Filament\Resources\VendorResource\Pages;

use App\Filament\Resources\VendorResource;
use App\Models\Vendor;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ListVendors extends ListRecords
{
    protected static string $resource = VendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        Action::make("Import Vendors")
            ->form([
                    FileUpload::make('csv_file')
                        ->label('Vendors CSV')
                        ->acceptedFileTypes(['text/csv'])
                        ->storeFiles(false)
                        ->required(),
            ])
            ->action(function (array $data , $record):void {
                DB::beginTransaction();
                $createdRecords = [];

                try {
                    $csvFile = $data['csv_file']->get();
                    $rows = explode("\n", $csvFile);
                    $headers = str_getcsv(array_shift($rows));

                    $vendors = [];
                    $vendorNames = [];

                    // Format CSV into array
                    foreach ($rows as $row) {
                        $values = str_getcsv($row, ",");
                        if (count($values) === count($headers)) {
                            $rowData = array_combine($headers, $values);

                            // Check for duplicate vendor names
                            if (!in_array($rowData['Vendor'], $vendorNames)) {
                                $vendors[] = $rowData;
                                $vendorNames[] = $rowData['Vendor'];
                            }
                        }
                    }

                    $newRecords = [];

                    foreach ($vendors as $vendor) {
                        $newRecords[] = [
                            'vendor' => $vendor['Vendor'],
                            'business_name' => $vendor['Business Name'],
                            'abn' => $vendor['A.B.N.'],
                            'email_enquiries' => $vendor['Email (enquiries)'],
                            'name' => $vendor['Name'],
                            'phone' => $vendor['phone'],
                            'phone_general_enquiries' => $vendor['phone (general enquries)'],
                            'fax' => $vendor['fax'],
                            'address' => $vendor['address'],
                            'postal_address' => $vendor['postal address'],
                            'website' => $vendor['website'],
                            'portal' => $vendor['portal'],
                            'email_orders' => $vendor['Email (orders)'],
                            'warranty_support_info' => $vendor['Warranty & Support info'],
                            'buyers_guide' => $vendor['Buyers guide'],
                            'comments' => $vendor['Comments'],
                            'confirmed' => $vendor['Confirmed'] == 'Y',
                        ];
                    }

                    // Insert data into the database and collect created models
                    if (!empty($newRecords)) {
                        foreach ($newRecords as $record) {
                            $createdRecords[] = Vendor::create($record); // Adjust to your model namespace
                        }
                    }
                    DB::commit();

                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Failed to import vendors');
                    Log::error($e->getMessage());
                }

            })
        ];
    }
}
