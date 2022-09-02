<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Spotify Widget</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    @csrf
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh">
        <div class="spinner-grow" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
        let url = window.location.href;
        url = url.replace("callback","redirect");
        url = url.replace("#","?");
        var tkn = $('input[name=_token').val();
        var hash = window.location.hash.substr(1);
        var result = hash.split('&').reduce(function (res, item) {
            var parts = item.split('=');
            res[parts[0]] = parts[1];
            return res;
        }, {});
        // console.log(result.token_type + ' ' +result.access_token);

        $.ajax({
            method: "GET",
            url: "https://api.spotify.com/v1/me",
            headers: {
            "Content-Type":    "application/json",
            "Authorization":   result.token_type + ' ' +result.access_token,
            },
            success: function(result) {
                // console.log(result);
                $.ajax({
                    type: "PUT",
                    data : {
                        'spotify_id' : result.id,
                        'display_name' : result.display_name,
                        'email' : result.email,
                        'images' : result.images[0].url,
                    },
                    headers: {
                        'X-CSRF-TOKEN': tkn},
                    url: url,
                    success: function (response) {
                        // console.log(response.response);
                        console.log(response.goto);
                        window.location.replace(response.goto);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });

            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
            
        });

    </script>
</body>
</html>
