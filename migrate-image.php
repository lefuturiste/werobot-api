<?php
require 'vendor/autoload.php';

$client = new GuzzleHttp\Client(['http_errors' => false]);

$token = "W4rkq86fqlSewUmR0yBcXzMHLLwl1Cym";

// fetch all posts
// for each image: verify if the image is too small and if the case, update the image to original size

$posts = json_decode(file_get_contents('https://api.werobot.fr/post'), 1)['data']['posts'];

foreach ($posts as $post) {
    echo "> Now migrating " . $post['id'] . " \n";

//    $path = './tmp/' . uniqid();
//    file_put_contents($path, file_get_contents($post['image']));
//    list($width) = getimagesize($path);
//    echo $width . " \n";
//    if ($width < $min) {
//        $post['image'] = str_replace('/50.', '/original.', $post['image']);
//    } else {
//        break;
//    }

    $images = [];
    $post = json_decode(file_get_contents('https://api.werobot.fr/post/' . $post['slug']), 1)['data']['post'];
    $images[] = $post['image'];
    preg_match_all('/https:\/\/static\.werobot\.fr\/blog\/bob-ross\/[a-zA-Z0-9]+\.[a-z]{3}/m', $post['content'], $matches, PREG_SET_ORDER, 0);
    foreach ($matches as $match) {
        if (isset($match[0])) {
            $images[] = $match[0];
        }
    }
    foreach ($images as $image) {
        echo ".        > migrating : ";
        echo $image . " \n";
        // dont migrate if it's a gif
        if (str_replace('.gif', '', $image) === $image) {
//            $response = $client->post("https://api.werobot.fr/image-upload", [
//                "json" => [
//                    "image" => $image
//                ],
//                "headers" => [
//                    "Authorization" => "Bearer " . $token,
//                    "Accept" => "application/json"
//                ]
//            ]);
//            if ($response->getStatusCode() === 200) {
//                $body = json_decode($response->getBody()->getContents(), true);
//                $newUrl = str_replace('original', '50', $body['data']['url']);
//                echo ".        > got new image by requesting the API: " . $body['data']['id'] . " \n";
//                $post['content'] = str_replace($image, $newUrl, $post['content']);
//                if ($image === $post['image']) {
//                    $post['image'] = $newUrl;
//                }
//            } else {
//                echo "> ERROR uploading image : \n";
//                var_dump($response->getStatusCode());
//                var_dump(json_decode($response->getBody()->getContents(), true));
//                echo "> END OF DEBUG \n";
//                exit();
//            }
//
            $re = '/\/([a-z 0-9]+)\/50.[a-z]{3,4}/m';
            preg_match_all($re, $image, $matches, PREG_SET_ORDER, 0);
            $imageId = $matches[0][1];
//            $imageApi['created_at'] = $post['created_at'];

        } else {
            echo ".        > abort because of a .gif image \n";
        }
    }
//    $response = $client->put("https://api.werobot.fr/post/" . $post['id'], [
//        'json' => [
//            'image' => $post['image']
//        ],
//        "headers" => [
//            'Authorization' => 'Bearer ' . $token,
//            'Accept' => 'application/json'
//        ]
//    ]);
//
//    if ($response->getStatusCode() === 200) {
//        echo ".        > changed the post by requesting the API succeed \n";
//    } else {
//        echo "> ERROR updating the content of the post : \n";
//        var_dump($response->getStatusCode());
//        var_dump(json_decode($response->getBody()->getContents(), true));
//        echo "> END OF DEBUG \n";
//        exit();
//    }

    echo ".        > end of migration for this post \n";
}
