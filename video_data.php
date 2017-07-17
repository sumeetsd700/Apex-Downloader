<?php

    // Returns the video array
    function fetch_url_list($playlist_url) {
        $url_list = array();

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 10.10; labnol;) ctrlq.org");
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $html = curl_exec($curl);
        curl_close($curl);

        return $url_list;
    }

    function getVideoInfo($link) {
    	$data = json_decode(file_get_contents('http://www.youtube.com/oembed?url=' . $link . '&format=json'), true);
    	return $data['thumbnail_url'];
    }

    // A function which generates the download url
    function getVideoUrl($link, $format) {

    	$vid_size = getVideoInfo($link);
        $vid_id = explode('=', $link)[1];

        parse_str(file_get_contents('https://www.youtube.com/get_video_info?video_id='.$vid_id), $file);

        $title = $file['title'];
        $streams = explode(',', $file["url_encoded_fmt_stream_map"]);

    	foreach($streams as $item) {
            parse_str($item, $data);
            $data['type'] = explode(';', $data['type'])[0];
            if(stripos($data['type'], $format) !== false) {
            	return array($data['url'], $title, $vid_size);
            }
        }
        return false;
    }


    // Function that returns playlist video ids
    function getPlaylistVideoId($html, $arr) {
        foreach($html->find('tr') as $ele) {
            if(isset($ele->attr['data-video-id'])) {
                array_push($arr, $ele->attr['data-video-id']);
            }
        }
        return $arr;
    }

    // Function that returns playlist videos array
    function getPlaylistVideos($url) {
        $main_page = file_get_contents($url);

        $html = new simple_html_dom();
        $html->load($main_page);

        $arr = array();
        $arr = getPlaylistVideoId($html, $arr);

        while(count($html->find('button[class=load-more-button]')) != 0) {

            $data = file_get_contents('https://www.youtube.com' . $html->find('button[class=load-more-button]', 0)->attr['data-uix-load-more-href']);

            $data = json_decode($data, true)['content_html'];
            $html->load($data);
            $arr = getPlaylistVideoId($html, $arr);
        }
        return $arr;
    }
?>
