<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable=['questions','title'];
    public static function createIfNotSimilar($content, $threshold = 80)
    {
        // First check for exact match
        $existing = self::where('questions', $content)->first();
        if ($existing) {
            return $existing;
        }

        // Get all sentences to compare (for small datasets)
        // For large datasets, consider full-text search or other solutions
        $allSentences = self::all();

        foreach ($allSentences as $sentence) {
            similar_text($content, $sentence->content, $percent);
            if ($percent >= $threshold) {
                return $sentence;
            }
        }

        return self::create(['questions' => $content]);
    }
}
