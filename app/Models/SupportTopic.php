<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SupportTopic
 *
 * @property int $id
 * @property string $topic
 * @property string|null $content
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupportTopic whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupportTopic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupportTopic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupportTopic whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupportTopic whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $category
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupportTopic whereCategory($value)
 * @property string|null $fts_doc
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SupportTopic whereFtsDoc($value)
 */
class SupportTopic extends Model
{
    public static function getSearchIndexConfiguration() {
        return [
            'driver' => env('DB_CONNECTION'),
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'storage' => env('SUPPORT_TOPICS_INDEX', '/var/help_topic_index/')
        ];
    }
}
