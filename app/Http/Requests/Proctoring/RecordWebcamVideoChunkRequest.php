<?php

namespace App\Http\Requests\Proctoring;

use Illuminate\Foundation\Http\FormRequest;

class RecordWebcamVideoChunkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (int) $this->route('attempt')->student_id === (int) auth()->id()
            && $this->route('attempt')->submitted_at === null;
    }

    public function rules(): array
    {
        return [
            'video' => ['required', 'file', 'max:8192'],
            'mime_type' => ['required', 'in:video/webm,video/mp4,video/ogg'],
            'chunk_index' => ['required', 'integer', 'min:0', 'max:1000000'],
        ];
    }
}
