<!DOCTYPE html>
<html>
    <head>
        <title>Youtube Downloader</title>
        <link href="index.css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Devonshire" rel="stylesheet">
    </head>
    <body>
        <h1>APEX Downloader</h1>
        <p class="sub-title"> Downloading youtube videos was never so easy...</p>
        <div class="form-container">
            <div class="user-inputs">
                <input type="url" id="link" name="link" placeholder="Paste the youtube video / playlist / channel link here.." autocomplete="off"  required />
                <select id="fmt" name="format" required >
                    <option selected value="mp4">mp4 720</option>
                    <option value="mp4">mp4 480</option>
                    <option value="3gp">3gp 360</option>
                </select>
                <input type="submit" id="submit-button" />
            </div>

            <div class="meter">
                <span style="width: 100%"></span>
            </div>

            <div class="section">Bulk Video Download Section</div>
            <div class="batchDownload">
                Your Download links will appear here..<br /><br />
                Tip: copy the text and open your download manager ex. IDM and add a batch download from clipboard
            </div>

            <div class="section">Individual Video Download Section</div><br /><br />
            <div class="video-list">
                <table style="width: 100%;">
                    <thead>
                        <tr><th>#</th><th>Thumbnail</th><th>Video Title</th><th>Download</th></tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $(document).ready(function() {

                var old_url = "";

                $('#submit-button').click(function() {
                    var url = $('#link').val();
                    var format = $('#fmt').val();
                    var url_list = "";

                    if($.trim(url) != '' && $.trim(format) != '' && url != old_url) {
                        old_url = url;
                        $("tbody").html("");
                        $(".batchDownload").html("");


                        var data = {'link': url, 'format': format};
                        $.ajax({
                            url: "index_web.php",
                            dataType: "json",
                            type: "POST",
                            data: data,
                            success: function(result) {
                                result.forEach(function(item) {
                                    var row = "<tr><td style='text-align: center; width: 5%; color: #f4f4f4; font-family: ubuntu; padding: 2px;'>" + item.vid_counter + "</td>";

                                    row += "<td style='text-align: center;'><img style='width: 80%; height: auto; border-radius: 5px; overflow: hidden; padding: 10px;' src=" + item.thumbnail_url + " /></td>";

                                    row += "<td style='text-align: left; width: 50%; color: #f4f4f4; font-family: ubuntu; font-size: 14px; padding: 10px; overflow: hidden;'>" + item.title + "</td>";

                                    row += "<td style='text-align: center; width: 25%'><a style='text-decoration: none; color: #f0f0f0; font-weight: bold; background-color: #444; padding: 10px 20px; font-family: ubuntu; font-size: 15px;' href=" + item.video_url + " download> Download</a></td></tr><br /><br />";

                                    $(".batchDownload").append(item.video_url + '<br/>');
                                    $("tbody").append(row);
                                });
                            },
                            error: function() {
                                $(".batchDownload").html("<h4 class='error-fetching'>Sorry, we encountered an error processing your request</h4>");
                            },
                            complete: function() {
                                $(".multi-download").text("");
                            }
                        });
                    }
                });
            });
        </script>
    </body>
</html>
