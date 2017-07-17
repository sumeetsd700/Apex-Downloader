<?php
    header("Content-Type: application/json;charset=utf-8");
    header('Access-Control-Allow-Origin: *');

    require 'simple_html_dom.php';
    require 'fileInfo.php';
    require 'video_data.php';

    // The main function
    function main($link, $format) {
        $playlist_template = "https://www.youtube.com/playlist?list=";
        $video_template = "https://www.youtube.com/watch?v=";
        $channel_template = "https://www.youtube.com/channel/";
        $user_template = "https://www.youtube.com/user/";

        $output_dir = getcwd() . '/downloads/';
        $vid_array = array();

        $playlist = $channel = $user = 0;

        parse_str($link, $parser);

        if(isset($parser['list'])) {
            // a playlist video
            array_push($vid_array, $link);
        }
        else if(isset($parser['https://www_youtube_com/watch?v'])) {
            // a single video
            array_push($vid_array, $link);
        }
        else if(isset($parser['https://www_youtube_com/playlist?list'])) {
            // a playlist
            $vid_array = getPlaylistVideos($link);
            $playlist = 1;
        }
        else {
            echo "others\n";
        }

        $vid_counter = 1;
        $result = array();
        foreach($vid_array as $link) {
            if($playlist) {
                $link = $video_template . $link;
            }

            $data = getVideoUrl($link, $format);

            if($data !== false) {
                $size = curl_get_file_size($data[0]);
                $thumbnail_link = $data[2];
                $title = $data[1];

                array_push($result, array("vid_counter" => $vid_counter++, "title" => $title, "thumbnail_url" => $thumbnail_link, "video_url" => $data[0]));
            }
            else {
                array_push($result, array("vid_counter" => $vid_counter++, "title" => "", "thumbnail_url" => "", "video_url" => ""));
            }
        }
        echo json_encode($result);
    }

	if(isset($_POST['link']) && isset($_POST['format'])) {
        main($_POST['link'], $_POST['format']);
	}
?>
