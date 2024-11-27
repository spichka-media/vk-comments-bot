<?php

$env = parse_ini_file('.env');

$data = json_decode(file_get_contents('php://input'));


if (!isset($data->secret) || !hash_equals($env['SECRET'], $data->secret)) {
    return;
}

if (!isset($data->type) || $data->type !== "wall_post_new") {
    return;
}

$request_params = [
    'message' => $env['COMMENT'],
    'owner_id' => $env['GROUP_ID'],
    'access_token' => $env['ACCESS_TOKEN'],
    'post_id' =>  filter_var($data->object->id, FILTER_SANITIZE_NUMBER_INT),
    'v' => $env['VK_API_VERSION']
];

$get_params = http_build_query($request_params);

file_get_contents('https://api.vk.com/method/wall.createComment?'. $get_params);

echo('ok');
