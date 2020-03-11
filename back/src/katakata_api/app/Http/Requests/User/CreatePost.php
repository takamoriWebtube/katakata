<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreatePost extends FormRequest
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
            'password' => 'bail|required|string|confirmed|max:255|min:8',
            'password_confirmation' => 'bail|required|string|max:255|min:8',
        ];
    }

    public function messages()
    {
      return [
        'password.required'     => 'パスワードを入力してください',
        'password.string'       => 'パスワードは文字列で入力してください',
        'password.confirmed'    => '確認用パスワードと一致しません',
        'password.max'          => 'パスワードの文字数が多すぎます。',
        'password.min'          => 'パスワードは8文字以上です。',
        'password_confirmation.required'        => '確認用パスワードを入力してください',
        'password_confirmation.string'          => '確認用パスワードは文字列で入力してください',
        'password_confirmation.max'             => '確認用パスワードの文字数が多すぎます。',
        'password_confirmation.min'             => '確認用パスワードは8文字以上です',
      ];
    }
}
