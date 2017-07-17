<?php
    function convertSize($size) {
        $count = 0;
        while($size > 1024) {
            $size = $size/1024;
            $count++;
        }

        $size = round($size);
        if($count == 0) return $size . 'Bytes';
        else if($count == 1) return $size . 'KB';
        else if($count == 2) return $size . 'MB';
        else if($count == 3) return $size . 'GB';
        else if($count == 4) return $size . 'TB';
        else return $size;
    }

    function curl_get_file_size( $url ) {
        $result = -1;
        $curl = curl_init( $url );

        curl_setopt( $curl, CURLOPT_NOBODY, true );
        curl_setopt( $curl, CURLOPT_HEADER, true );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
        //curl_setopt( $curl, CURLOPT_USERAGENT, get_user_agent_string() );

        $data = curl_exec( $curl );
        curl_close( $curl );

        if( $data ) {
            $content_length = "unknown";
            $status = "unknown";

            if( preg_match( "/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches ) ) {
                $status = (int)$matches[1];
            }

            if( preg_match( "/Content-Length: (\d+)/", $data, $matches ) ) {
                $content_length = (int)$matches[1];
            }

            // http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
            if( $status == 200 || ($status > 300 && $status <= 308) ) {
                    $result = $content_length;
            }
        }

        return convertSize($result);
    }
?>
