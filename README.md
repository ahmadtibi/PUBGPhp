# PUBGPhp
A small php wrapper that fetches stats from the pubg website

# Installation

Just download the PUBGPhp.php file and require it in your project
```
require_once('PUBGPhp.php');
$pubgapi = new PUBGPhp('YOUR-API-KEY');
```


# Usage and examples

Get a player's stats

```
$pubgapi->player_all('nowidont');
```

Get a player's stats with filters
```
$pubgapi->player_with_filter('nowidont','solo','eu');
```

Get a player's certain stat
```
//When using this function make sure to use the correct label (use postman to make sure) in this case the label is "Win %"
$win_rating = $pubgapi->getStat('nowidont','Win %','solo');
```
