<?php

namespace App\Models\Academic;

use App\Models\Academic\AcaQuestionLibrary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcaExamSet extends Model
{
    use SoftDeletes;

    protected $table = 'aca_exam_sets';

    protected $fillable = [
        'title',
        'topic',
        'question_type',
        'total_questions',
        'easy_count',
        'medium_count',
        'hard_count',
        'qt1_count',
        'qt2_count',
        'duration_minutes',
        'total_marks',
        'ai_reasoning',
        'question_ids',
        'custom_marks',
        'randomization_seed',
        'status',
        'aca_created_by',
        'aca_updated_by',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'question_ids'  => 'array',
        'custom_marks'  => 'array',
    ];

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    public function getQuestions()
    {
        return AcaQuestionLibrary::whereIn('id', $this->question_ids)
            ->get()
            ->keyBy('id');
    }

    public function getQuestionsInOrder(): \Illuminate\Support\Collection
    {
        $keyed = $this->getQuestions();
        return collect($this->question_ids)->map(fn($id) => $keyed[$id] ?? null)->filter();
    }

    public function getDifficultyBreakdownAttribute(): array
    {
        $total = $this->total_questions ?: 1;
        return [
            'easy'   => ['count' => $this->easy_count,   'pct' => round($this->easy_count   / $total * 100)],
            'medium' => ['count' => $this->medium_count, 'pct' => round($this->medium_count / $total * 100)],
            'hard'   => ['count' => $this->hard_count,   'pct' => round($this->hard_count   / $total * 100)],

            'qt1'   => ['count' => $this->qt1_count,   'pct' => round($this->qt1_count   / $total * 100)],
            'qt2'   => ['count' => $this->qt2_count,   'pct' => round($this->qt2_count   / $total * 100)],
        ];
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

