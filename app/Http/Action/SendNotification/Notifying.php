<?php
namespace App\Http\Action\SendNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;
class Notifying extends Notification implements ShouldQueue
{
    use Queueable;
    private $title;
    private $preview;
    private $date_time;

    public function __construct(array $data)
    {
        $this->title = $data['title'];
        $this->preview = $data['preview'];
        $this->date_time = now();
    }
    public function via($notifiable)
    {
        Log::info('Notifying via FCM:', ['notifiable' => $notifiable]);
        return [FcmChannel::class];
    }
    // public function toFcm($notifiable)
    // {
    //     return FcmMessage::create()
    //         ->setData([
    //             'title' => 'Congratulations!',
    //             'body' => 'You have won the betting game!',
    //         ]);
    // }

    public function toFcm($notifiable): FcmMessage
    {
        Log::info('Sending....Notificatino');
        try {
         
            return FcmMessage::create()
                ->notification(
                    FcmNotification::create()
                        ->title($this->title)
                        ->body($this->preview)
                )
                ->data([
                    // 'date_time' => $this->date_time,
                    'type' => 'staff_notification',
                ]);

        } catch (\Throwable $e) {

            Log::error("🔥 REAL FCM ERROR", [
                "notifiable" => get_class($notifiable),
                "id" => $notifiable->id,
                "token" => $notifiable->routeNotificationForFcm(),
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);

            throw $e; // this triggers NotificationFailed
        }
       
    }
}