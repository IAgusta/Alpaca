<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserContentProgress extends Model
{
    // Specify the table name
    protected $table = 'user_content_progress';

    // Fields that can be mass-assigned
    protected $fillable = [
        'user_id',
        'module_id',
        'module_content_id',
        'read',
        'read_at',
        'is_correct',
        'selected_answer',
        'submitted_at',
    ];

    // Define relationships
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function module() {
        return $this->belongsTo(Module::class);
    }

    public function content() {
        return $this->belongsTo(ModuleContent::class, 'module_content_id');
    }

    // Helper method to check if a content is read
    public static function isContentRead($userId, $contentId) {
        return self::where('user_id', $userId)
            ->where('module_content_id', $contentId)
            ->where('read', true)
            ->exists();
    }

    // Helper method to mark a content as read
    public static function markContentAsRead($userId, $contentId) {
        return self::updateOrCreate(
            [
                'user_id' => $userId,
                'module_content_id' => $contentId,
            ],
            [
                'read' => true,
                'read_at' => now(),
            ]
        );
    }

    // Helper method to mark a content as completed (for exercises)
    public static function markContentAsCompleted($userId, $contentId, $isCorrect, $selectedAnswer) {
        return self::updateOrCreate(
            [
                'user_id' => $userId,
                'module_content_id' => $contentId,
            ],
            [
                'is_correct' => $isCorrect,
                'selected_answer' => $selectedAnswer,
                'submitted_at' => now(),
            ]
        );
    }
}