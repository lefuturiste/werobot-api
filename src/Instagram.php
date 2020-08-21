<?php

namespace App;

class Instagram
{
    public function getMedias()
    {
        // $instagram = new \InstagramScraper\Instagram();
        // $medias = [];
        // foreach ($instagram->getMedias('werobot') as $media) {
        //     $dateTime = (new \DateTime("@{$media->getCreatedTime()}"))->format('Y-m-d H:i:s');
        //     array_push($medias, [
        //         'id' => $media->getId(),
        //         'caption' => $media->getCaption(),
        //         'link' => $media->getLink(),
        //         'images' => [
        //             'thumbnail' => $media->getImageThumbnailUrl(),
        //             'low' => $media->getImageLowResolutionUrl(),
        //             'standard' => $media->getImageStandardResolutionUrl(),
        //             'high' => $media->getImageHighResolutionUrl()
        //         ],
        //         'created_at' => $dateTime,
        //     ]);
        // }
        // return $medias;
        //die();
        $json = file_get_contents("https://www.instagram.com/graphql/query/?query_hash=e769aa130647d2354c40ea6a439bfc08&variables=%7B%22id%22%3A%225408546825%22,%20%22first%22%3A%2250%22,%20%22after%22%3A%20%22%22%7D");
        // $html = file_get_contents("https://www.instagram.com/werobot/");

        // $matches = [];
        // $re = '/window._sharedData = ({"config":[\S\s]+"});/m';
        // $doMatche = preg_match_all($re, $html, $matches, PREG_SET_ORDER, 0);
        // $json = $matches[0][1];
        $data = json_decode($json, true);
        // $raw = ($data['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges']);

        $raw = $data['data']['user']['edge_owner_to_timeline_media']['edges'];
        $medias = [];
        foreach ($raw as $rawMedia) {
            $rawMedia = $rawMedia['node'];

            $caption = NULL;
            if (count($rawMedia['edge_media_to_caption']['edges']) > 0) {
                $caption = $rawMedia['edge_media_to_caption']['edges'][0]['node']['text'];
            }

            array_push($medias, [
                'id' => $rawMedia['id'],
                'caption' => $caption,
                'thumbnail' => $rawMedia['thumbnail_src'],
                'original' => $rawMedia['display_url'],
                'link' => 'https://www.instagram.com/p/' . $rawMedia['shortcode'] . '/',
                'taken_at' => $rawMedia['taken_at_timestamp']
            ]);
        }
        return $medias;
        // $instagram = new \InstagramScraper\Instagram();
        // $medias = [];
        // foreach ($instagram->getMedias('werobot') as $media) {
        //     $dateTime = (new \DateTime("@{$media->getCreatedTime()}"))->format('Y-m-d H:i:s');
        //     array_push($medias, [
        //         'id' => $media->getId(),
        //         'caption' => $media->getCaption(),
        //         'link' => $media->getLink(),
        //         'images' => [
        //             'thumbnail' => $media->getImageThumbnailUrl(),
        //             'low' => $media->getImageLowResolutionUrl(),
        //             'standard' => $media->getImageStandardResolutionUrl(),
        //             'high' => $media->getImageHighResolutionUrl()
        //         ],
        //         'created_at' => $dateTime,
        //     ]);
        // }
        // return $medias;
    }
}