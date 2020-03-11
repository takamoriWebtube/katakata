<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\PreUser;

class SignUpPost extends FormRequest
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
            'name' => 'bail|required|string|max:40',
            'email' => ['bail',
                        'required',
                        'string',
                        'email',
                        'unique:users,email',
                        'max:40',
                        function ($attribute, $value, $fail) {
                            $preuser = PreUser::where('email', $value)->first();
                            if ($preuser) {
                                if ($preuser->created_at > date("Y-m-d H:i:s",strtotime("-1 hour"))) {
                                    return $fail('送信したメールアドレスのurlから、本登録へお進みください。');
                                }
                                PreUser::where('email', $value)->delete();
                            }
                        }],
        ];
    }

    public function messages()
    {
      return [
        'name.required'     => '名前を入力してください',
        'name.string'       => '名前は文字列で入力してください',
        'name.max'          => '名前は40文字以内で入力してください',
        'email.required'    => 'メールアドレスを入力してください',
        'email.unique'      => 'そのユーザーはすでに登録されています',
        'email.string'      => 'メールアドレスは文字列で入力してください',
        'email.email'       => 'メールアドレスの形式ではありません',
        'email.max'         => 'メールアドレスは255文字以内で入力してください',
      ];
    }
}
