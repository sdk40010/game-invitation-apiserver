<?php

namespace App\Http\Requests\Invitation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

/**
 * 募集の追加・更新用のリクエスト
 */
class UpsertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'description' => '',
            'start_time' => 'required|before:end_time',
            'end_time' => 'required|after:start_time',
            'capacity' => 'required|min:1|max:10',
            'tags' => 'array|max:10',
        ];
    }

    /**
     * バリデーション済みの募集データを取得する
     * 
     * @return array
     */
    public function getInvitationData()
    {
        $validated = $this->validator->validated();
        unset($validated['tags']);
        return $validated;
    }

    /**
     * 既存のタグと新しいタグで分類されたタグデータを取得する
     * 
     * @return array
     */
    public function getTagsData() {
        $existing = [];
        $new = [];

        foreach ($this->validated()['tags'] as $tag) {
            if (array_key_exists('id', $tag)) {
                $tag['count'] += 1;
                $existing[] = $tag;
            } else {
                $new[] = $tag;
            }
        }

        return [
            'existing' => $existing,
            'new' => $new
        ];

    }
}
