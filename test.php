<?php
require_once('PUBGPhp.php');
$pubgapi = new PUBGPhp('caf8c332-16b1-47aa-aec5-0093c4957fee');
//Gets the win ratio for the player and returns an array that contains all the regions with the respective Win ratio.
$win_rating = $pubgapi->getStat('test','Win %','solo');
//Gets stats for a certain player.
$pubgapi->player_all('lol');
//Gets stats for a certain player in the passed in mode and region in this case solo and eu
$pubgapi->player_with_filter('lol','solo','eu');
?>