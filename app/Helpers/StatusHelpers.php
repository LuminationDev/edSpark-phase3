<?php
namespace App\Helpers;

class StatusHelpers
{
    const DRAFT = 'DRAFT';

    const PENDING = 'PENDING';
    const UNPUBLISHED = 'UNPUBLISHED';

    const PUBLISHED = 'PUBLISHED';
    const ARCHIVED = 'ARCHIVED';
    const REJECTED = 'REJECTED';

    const ResourceStatus = [
        StatusHelpers::DRAFT => 'Draft',
        StatusHelpers::UNPUBLISHED => 'Unpublished',
        StatusHelpers::PUBLISHED => 'Published',
        StatusHelpers::PENDING => 'Pending',
        StatusHelpers::REJECTED => 'Rejected',
        StatusHelpers::ARCHIVED => 'Archieved',
    ];
    public static function getStatusList(){
        return self::ResourceStatus;
    }
}

