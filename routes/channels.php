<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('live-queue.{queue_id}', function () {

});
