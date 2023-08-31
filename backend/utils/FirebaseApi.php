<?php

namespace backend\utils;

use linslin\yii2\curl\Curl;
use yii\helpers\Json;

class FirebaseApi
{
    const API_ACCESS_KEY =
        "key=AAAAj4qNcDk:APA91bFIsUcUgmRaCdp0I2rEkXC4t6y8DHhIUfeQKLcjN6h964NNNWCJefh8OI4aaXKX5lSO92SgAUnuv3cIPv0OE8vx5TGrej9WY16VecSb_16l7r0Ovzsa7CfxDn4eQ2tjKRfI1efV";

    private $_topic = "/topics/all";

    private $_time_to_live;
    private $_title;
    private $_smallBody;
    private $_bigBody;
    private $_smallIcon;
    private $_largeIcon;

    private $_data;

    private $_headers;

    private $_fields;

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function setTimeToLive($timeToLive){
        $this->_time_to_live = $timeToLive;
    }

    public function setSmallBody($smallBody)
    {
        $this->_smallBody = $smallBody;
    }

    public function setBigBody($bigBody)
    {
        $this->_bigBody = $bigBody;
    }

    public function setSmallIcon($smallIcon)
    {
        $this->_smallIcon = $smallIcon;
    }

    public function setLargeIcon($largeIcon)
    {
        $this->_largeIcon = $largeIcon;
    }

    public function setTopic($token)
    {
        $this->_topic = $token;
    }

    private function _prepareData()
    {
        $this->_data = array(
            'title' => $this->_title,
            'message_body' => $this->_smallBody,
            'big_body' => $this->_bigBody,
            'small_icon' => $this->_smallIcon,
            'large_icon' => $this->_largeIcon
        );
    }

    private function _prepareFieldsWithTopic()
    {
        $this->_fields = array(
            'to' => $this->_topic,
            'time_to_live' => $this->_time_to_live,
            'data' => $this->_data
        );
    }

    private function _prepareHeader()
    {
        $this->_headers = array
        (
            'Authorization' => self::API_ACCESS_KEY,
            'Content-Type' => 'application/json'
        );
    }

    public function sendData()
    {
        $this->_prepareData();
        $this->_prepareHeader();
        $this->_prepareFieldsWithTopic();
        $curl = new Curl();
        $curl->setHeaders($this->_headers)
            ->setRequestBody(Json::encode($this->_fields))
            ->setOption(CURLOPT_RETURNTRANSFER, true)
            ->setOption(CURLOPT_SSL_VERIFYPEER, false)
            ->post('https://fcm.googleapis.com/fcm/send');
        $responseCode = $curl->responseCode;
        return $responseCode;
    }
}