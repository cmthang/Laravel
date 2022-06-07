<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Notifications\Action;

class SendMailGetFeedback extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $user;
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail_content = (new MailMessage)
        ->greeting('Dear '.$this->user["name"].',')
            ->from(env("MAIL_USERNAME"),'Anne Le from 3S Cloud')
            ->subject('3S Cloud Render Farm Values Your Opinion')
            ->line('Thank you for trusting in our render farm.')
            ->line($this->makeLinkIntoLine('We’d love to know how you found the experience of rendering on 3S Cloud Render Farm so would like to invite you to rate us on Google Business or Facebook.',
            ['Google Business','Facebook'], ['https://g.page/3scloudrenderfarm/review?rc','https://www.facebook.com/3SCloudRenderFarm/reviews/']))
            ->line('Your feedback is very important to us, so we hope you will take the time to fill it out.')
            ->line($this->makeStrongLine('Your Satisfaction - Our Inspiration'))
            ->line('Thank you so much.');
        return $mail_content;
    }

    private function makeLinkIntoLine($text,$action,$url): Htmlable {
        return new class($text,$action,$url) implements Htmlable {
            private $text;
            private $action;
            private $url;

            public function __construct($text,$action,$url) {
                $this->text = $text;
                $this->action = $action;
                $this->url = $url;
            } // end __construct()

            public function toHtml() {
                return $this->strip($this->addLink());
            } // end toHtml()

            private function addLink() {
                $content = '<p>';
                $content_text = $this->text;
                for($i=0; $i < count($this->action); $i++){
                    $action_link ='<a href="'.htmlspecialchars($this->url[$i]).'" target="_blank">'.$this->action[$i].'</a>';

                    $content_text = str_replace($this->action[$i],$action_link,$content_text);
                } 
                $content .= $content_text.'</p>';
                return $content;
            } // end btn()

            private function strip($text) {
                return str_replace("\n", ' ', $text);
            } // end strip()

        };
    } // end makeActionIntoLine()

    private function makeStrongLine($text): Htmlable {
        return new class($text) implements Htmlable {
            private $text;

            public function __construct($text) {
                $this->text = $text;
            } // end __construct()

            public function toHtml() {
                return $this->strip($this->makeStrong());
            } // end toHtml()

            private function makeStrong() {
                $content = '<span style="font-weight: 600;color: black;">'.$this->text.'</span>';
                return $content;
            } // end btn()

            private function strip($text) {
                return str_replace("\n", ' ', $text);
            } // end strip()

        };
    } // end makeActionIntoLine()

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
