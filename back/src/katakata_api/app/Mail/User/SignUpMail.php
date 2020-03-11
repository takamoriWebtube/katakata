<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SignUpMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $content;
    protected $url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content, $url)
    {
        //
        $this->content = $content;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->view('view.name');
        return $this
          ->from('hoge@exapmle.com') // 送信元
          ->subject('テスト送信') // メールタイトル
          ->view('mail.user.signup') // メール本文のテンプレート
          ->with(['content' => $this->content, 'url' => $this->url]);  // withでセットしたデータをviewへ渡す
    }
}
