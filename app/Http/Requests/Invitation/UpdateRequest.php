<?php

namespace App\Http\Requests\Invitation;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Invitation;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $invitation = Invitation::find($this->route('invitation'));
        return $invitation && $this->user()->can('updateOrDelete');
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
            'capacity' => 'required|min:1|max:10', // capacityの最小値は参加者の数で決まる
            'tags' => 'array|max:10',
            'tags_before_edit' => 'array|max:10'
        ];
    }

    /**
     * 既存のタグと新しいタグで分類されたタグデータを取得する
     * タグデータは更新が必要なタグだけを含んでいる
     * 
     * @return array
     */
    public function getTagsData()
    {
        $existing = [];
        $new = [];

        $shouldUpdateTags = array_filter(
            $this->validated()['tags'], 
            function ($value) {
                return !in_array($value, $this->validated()['tags_before_edit']);
            }
        );
        
        foreach ($shouldUpdateTags as $tag) {
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

    /**
     * タグ付を解除する必要のあるタグ一覧を取得する
     * 
     * @return array
     */
    public function getShouldDetachTags()
    {
        return array_filter(
            $this->validated()['tags_before_edit'],
            function ($value) {
                return !in_array($value, $this->validated()['tags']);
            }
        );
    }
}
