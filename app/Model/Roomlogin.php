<?php

App::uses('AppModel','Model');

class Roomlogin extends AppModel {

    // public $useTable = "roomlogins";


    // //ユーザーIDから所属しているルーム数を取得
    // public function countByUserId($userId) {

    //     $result = $this->find('count',['conditions' => ['user_id' => $userId,'request_flg' => 0,'request_delete_flg' => 0,'delete_flg' => 0]]);

    //     return $result;
    // }

    // //ユーザーIDから取得
    // public function getByUserId($userId) {

    //     $result = $this->find('first',['conditions' => ['user_id' => $userId,'request_flg' => 0,'request_delete_flg' => 0,'delete_flg' => 0]]);

    //     return $result;
    // }


    // //ユーザーIDとルームIDから数を取得
    // public function countByUserIdAndRoomId($userId,$roomId) {

    //     $result = $this->find('count',['conditions' => ['user_id' => $userId,'room_id' => $roomId,'request_flg' => 0,'request_delete_flg' => 0,'delete_flg' => 0]]);

    //     return $result;
    // }
    
}