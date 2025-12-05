<?php
namespace App\Http\Action\SendNotification;
use App\Models\Notification;
use InvalidArgumentException;
use App\Models\PersonFcmToken;
use App\Models\NotificationUser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\FirebasePushNotification;
use Illuminate\Support\Facades\Notification as LaravelNotification;

trait FcmSendNotification
{
    // public function sendFcmNotification($fcmToken)
    // {
    //     $deviceToken =$fcmToken; // Get this from the client-side
    //     $title = 'New Message!';
    //     $body = 'You have a new notification.';

    //     Notification::route('firebase', $deviceToken) // Using a custom 'firebase' channel
    //         ->notify(new FirebasePushNotification($title, $body, $deviceToken));

    //     return 'Notification sent!';
    // }
    public function sendFcmNotification($model, $people, $data)
    {
        Log::info('Reach Notification Function');
        $morphMapName = RelationMorphName($model);
        $people = $this->normalizeToCollection($people);
        Log::info('Notification Model',[
            'model'=>$model
        ]);
        Log::info('Model Map',[$morphMapName]);
        // Log::info('Authorized User',[UserData()]);

        // $user_ids = $people->pluck('id');
        $notification = Notification::updateOrCreate(
            [
                'notificationable_id' => $model->id,
                'notificationable_type' => $morphMapName,
            ],
            [
                'title' => $data['title'],
                'preview' => $data['preview'],
                'date_time' => now(),
                'created_by' => $model->created_by,
            ]
        );

        Log::info('Notification Person');

        $notificationPersons = [];
        // dd($people);
        // Log::info('notification', $data);
        foreach ($people as $person) {
            // dd($person->routeNotificationForFcm());
            $personMorphMapName = RelationMorphName($person);
            $notificationPersons[] = [
                'notification_id' => $notification->id,
                'staff_id' => $person->id,
                 'title' => $data['title'],
                'preview' => $data['preview'],
                // 'personable_type' => $personMorphMapName,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            // NotificationPerson::create($notificationData);
            Log::info('created notification person');
        }
        NotificationUser::insert($notificationPersons);
        // Batch send notifications (uses ShouldQueue)
        Log::info('Reach Notifcation Queue');
        // NotificationQueue::send($people, new Notifying($data));
        LaravelNotification::send($people, new Notifying($data));
        // dispatch(new Notifying($data));
        Log::info('Complete Notifcation Queue');

    }
    protected function normalizeToCollection($people)
    {
        // If $people is not already a collection, convert it to one
        if ($people instanceof Model) {
            return collect([$people]);
        } elseif (is_array($people)) {
            return collect($people);
        } elseif ($people instanceof Collection) {
            return $people;
        } else {
            throw new InvalidArgumentException('People should be a Model, array, or Collection.');
        }
    }

    public function getTokensByPerson($person_ids, $type)
    {
        return PersonFcmToken::whereIn('personable_id', $person_ids)
            ->where('personable_type', $type)
            ->pluck('fcm_token')->toArray();
    }
}