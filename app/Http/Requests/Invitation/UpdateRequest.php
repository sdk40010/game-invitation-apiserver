<?php

namespace App\Http\Requests\Invitation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $invitation = $this->route('invitation');
        return $invitation && $this->user()->can('updateOrDelete', $invitation);
    }

    /**
     * バリデーション前に行う処理
     */
    protected function prepareForValidation()
    {
        $this->tagsBeforeUpdate = $this->route('invitation')->tags->toArray();
        $this->participantsCount = $this->route('invitation')->participants->count();
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
            'capacity' => 'required|integer|min:'.$this->participantsCount.'|max:10',
            'tags' => 'array|max:10',
        ];
    }

    /**
     * 既存のタグと新しいタグで分類されたタグ一覧を取得する
     * タグ一覧は更新が必要なタグだけを含んでいる
     * 
     * @return array
     */
    public function getTagsData()
    {
        $existing = [];
        $new = [];


        $tagsShouldUpdated = array_filter(
            $this->validated()['tags'], 
            function ($value) {
                return !in_array($value, $this->tagsBeforeUpdate);
            }
        );
        
        foreach ($tagsShouldUpdated as $tag) {
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
     * タグ付を解除する必要があるタグ一覧を取得する
     * 
     * @return array
     */
    public function getTagsDataShouldDetached()
    {
        return array_filter(
            $this->tagsBeforeUpdate,
            function ($value) {
                return !in_array($value, $this->validated()['tags']);
            }
        );
    }
}
