<?php

namespace App\Filament\Resources\SchoolResource\Pages;

use App\Filament\Resources\SchoolResource;
use App\Models\School;
use App\Models\Site;
use Carbon\Carbon;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSchool extends CreateRecord
{
    protected static string $resource = SchoolResource::class;
    protected ?string $subheading = 'You can only create profiles for sites without existing profile. To edit sites with existing profile, go back to Schools listing.';
    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $schoolSiteId = $data['name'];
        $data['name'] = Site::where('site_id', $schoolSiteId)->first()->site_name;
        $data['site_id'] = $schoolSiteId;
        $latestSchool = School::orderBy('school_id', 'desc')->first();
        $nextSchoolId = ($latestSchool ? $latestSchool->school_id + 1 : 1);
        $data['school_id'] = $nextSchoolId;
        $data['owner_id'] = Auth::user()->id;
        $data['status'] = \App\Helpers\StatusHelpers::PUBLISHED;
        $data['created_at'] =Carbon::now() ;
        $data['updated_at'] =  Carbon::now();
        return $data;
    }


}
