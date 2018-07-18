<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
// use App\CV;

class NotifyCv extends Notification
{
    use Queueable;

    public $cv;
    public $device_tokens;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cv, $device_tokens)
    {
        //
        $this->cv = $cv;
        $this->device_tokens = $device_tokens;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        //firebase
        if(!empty($this->device_tokens)){
            return ['database','mail','broadcast','firebase'];
        }
        return ['database','mail','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDatabase($notifiable)
    {
        $cv = $this->cv;
        return [
            'cv' => $cv->getAttributes()
        ];
               
    }

    public function toBroadcast($notifiable)
    {
        $cv = $this->cv;
        return [
            'data' => [
                'cv' => $cv->getAttributes(),
                'type'=> 'cv'
            ]
        ];
               
    }

    public function toFirebase($notifiable)
    {
        $cv = $this->cv;

        return (new \Liliom\Firebase\FirebaseMessage)
            ->notification([
                'title' => $cv['job_name'],
                'body' => $cv['message'],
                'sound' => '', // Optional
            'icon' => '', // Optional
            'click_action' => '' // Optional
            ])
            ->setData([
            'cv' => $cv->getAttributes(),
            'type'=> 'cv'   
        ])
        ->setPriority('high'); // Default is 'normal'
      
    }

    public function toMail($notifiable)
    {
        $cv = $this->cv;
        return (new MailMessage)
                    ->subject($cv['message'])
                    ->greeting($cv['job_name'])
                    ->line($cv['message']);
                    // ->action('View Invoice', $url)
                    // ->line('Thank you for using our application!');
    }

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
