<?php ?>
<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Skillshare Shortener</title>

</head>

<style>
    legend {
        background-color: #000;
        color: #fff;
        padding: 3px 6px;
    }

    .result {
        width: 400px;
    }

    .control {
        font-size: .8rem;
        margin: 1rem 0;
    }

    input:invalid:not(:focus) {
        outline: 1px solid #f00;
    }
</style>

<body>

    <form>
        <fieldset>
            <legend>Shorten a URL</legend>

            <div class="control">
                <label for="url">Enter a URL:</label>
                <input type="url" name="url" id="url"
                       placeholder="https://example.com"
                       pattern="https://.*" size="20" required />
                <button type="button" id="url-submit">Shorten!</button>
            </div>
            <div class="result" id="url-result"></div>

        </fieldset>

        <fieldset>
            <legend>Find & Shorten URLs in Text</legend>

            <div class="control">
                <label for="text">Auto-link Text:</label>
                <textarea type="text" id="text" placeholder="Enter some text"></textarea>
                <button type="button" id="text-submit">Shorten!</button>
            </div>
            <div class="control" id="text-result-container" style="display: none;">
                <label for="text-result">Copy me!</label>
                <textarea class="result" id="text-result" readonly></textarea>
            </div>

        </fieldset>
    </form>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    window.jQuery = window.$ = jQuery;

    const encodeUrl = (url, callback) => {
        $.ajax({
            url: "http://sk.sh/_encode",
            type: "POST",
            dataType: "json",
            data: {url: url},
            success: callback,
        });
    };

    $('#url-submit').click(function() {
        encodeUrl($('#url').val(), (response) => {
            $('#url-result').html('<a href="'+response.short_link+ '">'+response.short_link+'</a>');
        });
    });

    // This is a mess and extremely naive/poorly performing
    $('#text-submit').click(function() {
        if ($('#text').val() === "") { return; }
        let matches = $('#text').val().match(/\b(https?:\/\/.*?\.[a-z]{2,4}\b)/g);
        $('#text-result-container').show();
        $('#text-result').val($('#text').val());
        if (!matches || matches.length === 0) { return; }
        for (let i=0; i<matches.length; i++) {
            let match = matches[i];
            encodeUrl(matches[i], (response) => {
                $('#text-result').val(
                    $('#text-result').val().replace(match, '<a href="' + response.short_link + '">' + response.short_link + '</a>')
                );
            });
        }
    });
</script>

</body>
</html>