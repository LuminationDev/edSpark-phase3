<?php

namespace App\Models;

use App\Helpers\ExtraContentCleaner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\Tags\HasTags;

class Software extends Model
{
    use
        HasFactory, HasTags;
    use Searchable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'softwares';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'post_title',
        'post_content',
        'post_excerpt',
        'post_date',
        'post_modified',
        'post_status',
        'author_id',
        'cover_image',
        'softwaretype_id',
        'template',
        'extra_content'
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function softwaretypes()
    {
        return $this->belongsToMany(Softwaretype::class);
    }

    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Like::class, 'post_id', 'id')->where('post_type', 'software');
    }

    public function bookmarks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bookmark::class, 'post_id', 'id')->where('post_type', 'software');
    }

    public function getSearchResult()
    {
        return [
            'title' => $this->post_title,
            'content' => strip_tags($this->post_content),
            'tags' => $this->tags,
            'author' => [
                'author_id' => $this->author->id ?? '',
                'author_name' => $this->author->full_name ?? '',
                'author_type' => $this->author->usertype->user_type_name ?? '',
            ],
        ];
    }

    private function cleanExtraContent($dataArray)
    {
        foreach ($dataArray as &$data) {
            $extraContent = $data['data']['extra_content'];

            foreach ($extraContent as $key => &$content) {
                if (isset($content['item']) && is_array($content['item'])) {
                    foreach ($content['item'] as $index => $item) {
                        if (is_array($item)) {
                            $allValuesEmpty = true;

                            // Check if all values of the current item are empty or null
                            foreach ($item as $value) {
                                if ($value !== null && $value !== "") {
                                    $allValuesEmpty = false;
                                    break;
                                }
                            }
                            if ($allValuesEmpty) {
                                unset($content['item'][$index]);
                            }
                        } elseif ($item === null || $item === "") {
                            unset($content['item'][$index]);
                        }
                    }

                    // If the 'item' key has become empty, remove it
                    if (empty($content['item'])) {
                        unset($extraContent[$key]);
                    }
                } else {
                    // If the content key has other empty keys, remove them
                    foreach ($content as $contentKey => $contentValue) {
                        if (empty($contentValue)) {
                            unset($extraContent[$key][$contentKey]);
                        }
                    }

                    // If the entire content key has become empty, remove it
                    if (empty($extraContent[$key])) {
                        unset($extraContent[$key]);
                    }
                }
            }

            $data['data']['extra_content'] = $extraContent;
        }

        return $dataArray;
    }

    public function toSearchableArray(): array
    {
        return [
            'title' => $this->post_title,
            'slug' => $this->post_title,
            'content' => $this->post_content,
        ];
    }

    protected $casts = [
        'cover_image' => 'array',
        'extra_content' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($software) {
            if ($software->extra_content) {
                $software->extra_content = ExtraContentCleaner::cleanExtraContent($software->extra_content);
            }
        });
        static::updating(function ($software) {
            if ($software->isDirty('extra_content')) {
                $software->extra_content = ExtraContentCleaner::cleanExtraContent($software->extra_content);
            }
        });
    }
}
