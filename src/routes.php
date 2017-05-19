<?php

$app->get(config('swagger-lume.routes.docs'), config('swagger-lume.controller.docs'));
$app->get(config('swagger-lume.routes.api'), config('swagger-lume.controller.api'));