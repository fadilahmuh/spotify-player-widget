<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Blood Coders</title>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" />

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500&display=swap"
        rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/skins/1/styles.css') }}">
    <script src="{{ asset('assets/skins/1/script.js') }}"></script>

</head>

<body>
    <div class="container">

        <div class="flex-item-1">
            <div class="cover"></div>
            <div class="bg"></div>
        </div>

        <div class="flex-item-2">

            <div class="info-holder">
                <div class="song">Lily</div>
                <div class="artist">Alan Walker</div>

                <div class="progress">
                    <div class="duration">01:28/03:55</div>
                    <div class="bar"></div>
                </div>


            </div>

        </div>

    </div>

</body>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
    <script>
        
        let run = async () => {
            while (true) {
                $.ajax({
                    method: "GET",
                    url: "https://api.spotify.com/v1/me/player/currently-playing",
                    headers: {
                    "Content-Type":    "application/json",
                    "Authorization":   "{{$user->access_token}}",
                    },
                    success: function(result) {
                        let artist = [];
                        for (let index = 0; index < result.item.artists.length; index++) {                   
                            artist.push(result.item.artists[index].name)
                        }
                        $('.artist').text(artist.join());
                        $('.cover').css('background-image', 'url(' + result.item.album.images[1].url + ')');
                        $('.song').text(result.item.name);
                        $('.duration').text(convert(result.progress_ms) +'/'+ convert(result.item.duration_ms));
                        $(':root').css('--progress-bar', bars(result.progress_ms,result.item.duration_ms)+'%' );
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }        
                });
                await sleep(1000)
            }
        }
            
        function convert(millis) {
            var minutes = Math.floor(millis / 60000);
            var seconds = ((millis % 60000) / 1000).toFixed(0);
            return minutes + ":" + (seconds < 10 ? '0' : '') + seconds;
        }

        function bars(prog, dur) {
            var percent = (prog / dur * 100);
            return percent;
        }

        const sleep = (delay) => {
            return new Promise(function(resolve) {
                setTimeout(resolve, delay);
            });
        }

        run();
    </script>
</html>