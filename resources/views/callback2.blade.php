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
    <input type="hidden" readonly name="client" readonly value="{{env('CLIENT_KEY')}}">
    <input type="hidden" readonly name="key" readonly value="{{env('SECRET_KEY')}}">
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh">
        <div class="spinner-grow" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
        var ck = $("input[name=client]").val();
        var sk = $("input[name=key]").val();
        const params = new Proxy(new URLSearchParams(window.location.search), {
        get: (searchParams, prop) => searchParams.get(prop),
        });
        // console.log(btoa(ck+':'+sk));
        var tkn = $('input[name=_token').val();
        // console.log(params.code);

        $.ajax({
            method: "POST",
            url: "https://accounts.spotify.com/api/token",
            data : 
            {
                'code' : params.code,
                'grant_type' : 'authorization_code',
                'redirect_uri' : 'http://spotify-player-widget.test/call-test?',
            },
            headers: {
                "Authorization":   'Basic ' + btoa(ck+':'+sk),
            },
            success: function(token) {
                console.log(token);
                console.log('===============================================');

                $.ajax({
                    method: "GET",
                    url: "https://api.spotify.com/v1/me",
                    headers: {
                    "Content-Type":    "application/json",
                    "Authorization":   token.token_type + ' ' +token.access_token,
                    },
                    success: function(user) {
                        console.log(user);
                        $.ajax({
                            type: "PUT",
                            data : {
                                'access_token' : token.access_token,
                                'refresh_token' : token.refresh_token,
                                'spotify_id' : user.id,
                                'display_name' : user.display_name,
                                'email' : user.email,
                                'images' : user.images[0].url,
                            },
                            headers: {
                                'X-CSRF-TOKEN': tkn},
                            url: 'http://spotify-player-widget.test/redirect2',
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

            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
            
        });

    </script>
</body>
</html>
