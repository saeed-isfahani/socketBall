<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Page</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-image: url('images/field.jpg');
            background-repeat: no-repeat;
            background-size: 100% 100vh;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        .player {
            position: absolute;
        }

        #add_left_player {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #0032b3;
            color: #ffffff;
            padding: 5px;
            border-radius: 7px;
        }

        #add_right_player {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #c60000;
            color: #ffffff;
            padding: 5px;
            border-radius: 7px;
        }

        #reset_ground {
            position: absolute;
            left: 49%;
            top: 10px;
            background-color: #444444;
            color: #ffffff;
            padding: 5px;
            border-radius: 7px;
        }
    </style>
</head>

<body>
    <button class="add-player" id="add_left_player" value="left" type="button">add player</button>
    <button class="add-player" id="add_right_player" value="right" type="button">add player</button>
    <button class="reset-ground" id="reset_ground" type="button">reset</button>
    <script type="module">
        let groundWidth = document.body.clientWidth;
        let groundHeight = document.body.clientHeight;

        $('.add-player').on('click', function(e) {
            let direction = $(this).val();
            let x;
            let y;
            if (direction == 'left') {
                x = (Math.round(Math.random(100, groundWidth) * 900) - 100);
                y = (Math.round(Math.random(100, groundHeight) * 600) - 100);
            } else {
                x = (groundWidth - Math.round(Math.random(100, groundWidth) * 900));
                y = (groundHeight - Math.round(Math.random(100, groundHeight) * 600));
            }

            $.ajax({
                url: "user_joined",
                data: {
                    direction,
                    x,
                    y
                },
                success: function(result) {

                }
            });
        })

        $('.reset-ground').on('click', function(e) {
            $('.player').remove();

            $.ajax({
                url: "reset-ground",
                data: {
                    action: 'reset'
                },
                success: function(result) {

                }
            });
        });

        Echo.channel('join').listen('PlayerJoinEvent', function(data) {
            let direction = data.direction;
            let x = data.x;
            let y = data.y;

            let $numberDiv = $('<img id="' + data.uniqueID + '" class="player" src="images/' + direction + '-player.png" />');
            $numberDiv.css({
                top: y,
                left: x
            });

            $('body').append($numberDiv);

            $('.player').draggable({
                stop: function(e) {
                    $.ajax({
                        url: "repostion",
                        data: {
                            id: $(this).attr('id'),
                            x: e.originalEvent.clientX,
                            y: e.originalEvent.clientY
                        },
                        success: function(result) {

                        }
                    });
                }
            });
        });

        Echo.channel('reposition').listen('PlayerRepositionEvent', function(data) {
            $('#' + data.id).css({
                top: data.y - 25,
                left: data.x - 20
            });
        });

        Echo.channel('reset-ground').listen('ResetGroundEvent', function(data) {
            if(data.action == 'reset'){
                $('.player').remove();
            }
        });
    </script>
</body>

</html>