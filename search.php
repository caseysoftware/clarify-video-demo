<?php
include 'creds.php';
include 'vendor/autoload.php';

$terms = $_POST['terms'];
$terms = preg_replace("/[^ A-Za-z0-9|]/", "", $terms);

$bundle = new Clarify\Bundle($apikey);
$items = $bundle->search($terms);

$total = (int) $items['total'];
$search_terms = json_encode($items['search_terms']);
$item_results = json_encode($items['item_results']);

$bundlekey = $items['_links']['items'][0]['href'];
$tracks = $bundle->tracks->load($bundlekey)['tracks'];

$mediaUrl = $tracks[0]['media_url'];
$duration = $tracks[0]['duration'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Clarify Video Demo</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
    <script src="js/jquery/jquery-ui-1.9.2.custom.min.js" type="text/javascript"></script>
    <script src="js/jquery/jquery.jplayer-2.6.0.min.js" type="text/javascript"></script>
    <script src="js/o3v_video_player.js" type="text/javascript"></script>

    <link rel="stylesheet" href="css/jquery-ui.custom.css"/>
    <link rel="stylesheet" href="css/o3v-player.css"/>

    <script type="text/javascript">
        $(document).ready(function() {

            // Set the path to the jplayer swf file.
            o3vPlayer.jPlayerOptions.swfPath = 'js/jquery';

            // Set to the playback URL for the video file(s).
            var mediaURLs = { m4v:"<?php echo $mediaUrl; ?>"};

            ////////////////////////////////////////////////////////
            // This is a sample search_terms array from a SearchCollection
            var searchTerms = <?php echo $search_terms; ?>;
            // This is a sample "ItemResult" object from a SearchCollection JSON
            // object. It is one item in the item_results array.
            var itemResult =  <?php echo $item_results; ?>;
            ////////////////////////////////////////////////////////

            // Create a player and add in search results marks

            var convDuration = <?php echo $duration; ?>;
            var player = o3vPlayer.createPlayer("#player_instance_1",mediaURLs,
                convDuration,{volume:0.5});
            o3vPlayer.addItemResultMarkers(player,convDuration,itemResult[0],searchTerms);

            ////////////////////////////////////////////////////////
            // Create words tags for SearchCollection.

            for (var i=0,c=searchTerms.length;i<c;i++) {
                var term = searchTerms[i].term;
                var dtag = document.createElement('div');
                $(dtag).addClass("o3v-search-tag o3v-search-color-"+i);
                $(dtag).text(term);
                $("#player_1_search_tags").append(dtag);
            }
            dtag = document.createElement('div');
            $(dtag).addClass("o3v-clear");
            $("#player_1_search_tags").append(dtag);
            ////////////////////////////////////////////////////////

        });
    </script>

</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Clarify Video Demo</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php">Home</a></li>
<!--                <li><a href="load.php">Load File</a></li>-->
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>
<?php if (0 == $total) { ?>
<em>There were no results found. <a href="index.php">Go back</a> and try some other terms.</em>
<?php } ?>
<br>
<div id="player_1_search_tags" class="o3v-search-tag-box"></div>
<div id="player_instance_1"></div>
</body>
</html>