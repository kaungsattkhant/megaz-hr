<?php

namespace App\Repositories\MeetingMinute;

interface MeetingMinuteRepositoryInterface
{
    public function list($request);

    public function updateOrCreate(array $data);

    public function detail($meetingMinute);

    public function delete($meetingMinute);
}
