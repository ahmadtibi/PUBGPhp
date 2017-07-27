<?php

/**
 * Class PUBGPhp
 * PHP wrapper to fetch data from pubgtracker
 *
 */
class PUBGPhp
{
    private $api_key;
    private $modes = ['duo', 'solo', 'squad'];
    private $regions = ['as', 'na', 'agg', 'eu'];
    public $url = 'https://pubgtracker.com/api/profile/pc/';
    private $opts;
    public function __construct($api_key)
    {
        $this->api_key = $api_key;
        $this->opts = array(
            'http' => array(
                'method' => "GET",
                'header' =>
                    "TRN-API-KEY:$this->api_key"
            )
        );
    }

    /** Fetches json so we dont have to repeat the same 4 lines in every function
     * @param $player
     */
    private function fetchJson($player)
    {
        $url = $this->url . $player;
        $context = stream_context_create($this->opts);
        $file = file_get_contents($url, false, $context);
        $json = json_decode($file, true);
        return $json;
    }

    /** Checks if the passed in region and mode is valid so we don't have to add if in every function.
     * @param $mode
     * @param $region
     * @throws ErrorException
     */
    private function valid_mode_region($mode, $region)
    {
        if (!in_array($mode, $this->modes)) {
            throw new ErrorException('Invalid game mode. available modes are duo,solo,squad');

        }
        if (!in_array($region, $this->regions)) {
            throw new ErrorException('Invalid region. available regions are na,as,eu,agg');
        }
    }

    /** Fetches everything for the passed in $player.
     * @param $player the player
     * @return mixed
     * @throws ErrorException if player not found or something wrong with the website api
     */
    public function player_all($player)
    {
        $json = $this->fetchJson($player);
        if ($json['AccountId'] === null) {
            throw new ErrorException("Player not found or stats haven't been updated");
        }
        return $json;
    }

    /** Gets the player stats with filtering (game mode and region).
     * @param string $player
     * @param string $mode the game mode default is duo
     * @param string $region the region default is na
     * @throws ErrorException when player isnt found or something wrong with the site
     */
    public function player_with_filter($player, $mode = 'duo', $region = 'na')
    {
        $this->valid_mode_region($mode, $region);
        $json = $this->fetchJson($player);
        if ($json['AccountId'] === null) {
            throw new ErrorException("Player not found or stats haven't been updated");
        }
        $data = array();
        foreach ($json['Stats'] as $stat) {
            if ($stat['Match'] == $mode && $stat['Region'] == $region) {
                $data = $stat;
                print_r($data);
            }
        }
    }

    /**Gets a certain stat for the player. This returns an array with the regions so keep that in mind ($stat['eu'],$stat['na'],$stat['as'],$stat['agg'])
     * @param $player
     * @param $label - LABEL very important note use postman or any other tool to figure out what label to use if you don't use the right label then it won't work
     * @param $mode the game mode - default is solo
     * @throws ErrorException
     * @return array
     */
    public function getStat($player, $label, $mode = 'solo')
    {
        if (!in_array($mode, $this->modes)) {
            throw new ErrorException('Invalid game mode.');
        }
        $json = $this->fetchJson($player);
        if ($json['AccountId'] === null) {
            throw new ErrorException("Player not found or stats haven't been updated");
        }
        $player_stats = array();
        foreach ($json['Stats'] as $stat) {
            if ($stat['Match'] == $mode) {
                foreach ($stat['Stats'] as $data) {
                    if ($data['label'] == $label) {
                        $player_stats[$stat['Region']] = $data['value'];

                    }
                }
            }
        }
        return $player_stats;
    }
    public function player_skill_rating($player,$mode = 'solo')
    {
        if (!in_array($mode, $this->modes)) {
            throw new ErrorException('Invalid game mode.');
        }
        $json = $this->fetchJson($player);
        if ($json['AccountId'] === null) {
            throw new ErrorException("Player not found or stats haven't been updated");
        }
        $player_stats = array();
        foreach ($json['Stats'] as $stat) {
            if ($stat['Match'] == $mode) {
                foreach ($stat['Stats'] as $data) {
                    if ($data['label'] == 'Rating') {
                        $player_stats[$stat['Region']] = $data['value'];

                    }
                }
            }
        }
        return $player_stats;
    }
}