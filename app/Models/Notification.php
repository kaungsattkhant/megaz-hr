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
    protected $fillable=['title','preview','notificationable_id','notificationable_type','created_by','date_time'];

    public function notificationUsers(){
        return $this->hasMany(\App\Models\NotificationUser::class);
    }
    public  function toUserMultipleDevice($tokens=null,$title=null,$body=null,$add_data){
        $click_action='http://127.0.0.1:8080';
        $icon=null;
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
                            ->setSound('default');
                            
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData($add_data);
    
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
