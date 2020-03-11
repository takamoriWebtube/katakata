<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreateMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
          ->from('hoge@exapmle.com') // 送信元
          ->subject('本登録が完了しました') // メールタイトル
          ->view('mail.user.create') // メール本文のテンプレート
          ->with(['user' => $this->user]);  // withでセットしたデータをviewへ渡す
    }
}
