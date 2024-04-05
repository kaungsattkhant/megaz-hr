<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
class Notification extends Model
{
    use HasFactory;
    protected $fillale=['title','preview','notificationable_id','notificationable_type','created_by'];

    public function notificationUsers(){
        return $this->hasMany(\App\Models\NotificationUser::class);
    }
    public  function toUserMultipleDevice($tokens=null,$title=null,$body=null,$icon,$click_action="https://slazh.com.mm",$add_data){
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
                            ->setSound('default')
                            ->setBadge(1)
                            ->setIcon($icon)
                            ->setClickAction($click_action);
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData($add_data);
        // $dataBuilder->addData(['id' => $noti->id]);
        // $dataBuilder->addData(['notification_count' => $this->where('is_read',0)->count()]);
        // $dataBuilder->addData(['type' => $noti->notify->notifiable_type]);
        // if($noti->notify->notifiable_type=='coupon'){
        //     $dataBuilder->addData(['code' => $noti->notify->code]);
        // }
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        // You must change it to get your tokens
        // $tokens = $model->pluck('device_token')->toArray();
        // $tokens = $model->pluck('device_token')->toArray();


        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

        $downstreamResponse->tokensToDelete();

        $downstreamResponse->tokensToModify();

        $downstreamResponse->tokensToRetry();

        $downstreamResponse->tokensWithError();
    }
}
