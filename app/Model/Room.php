<?php

App::uses('AppModel','Model');

/**
 * 外部キー
 * User.user_id(ルームの作成者)
 * 
 * アソシエーション
 * User
 */
class Room extends AppModel {

    public $useTable = "rooms";
    public $primaryKey = 'room_id';
    public $actsAs = ['Containable'];

    public $belongsTo = [
        'User' => [
            'foreignKey' => 'create_user_id',
        ],
    ];

    public $validate = [
        
        'room_name' => [
            [
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'ルーム名を入力してください'
            ],
            [
                'rule' => ['maxLength', 15],
                'message' => '15文字以内で入力してください'
            ],
            [
                'rule' => 'isUnique',
                'message' => 'このルーム名は使われています'
            ],
        ],
        
    ];

    /**
     * ルーム作成
     *
     * @param [array] $data(create_user_id,$room_name,created_at)
     * @return boolean
     */
    public function createRoom($data) {
        if (!$data) return false;

        if (!$this->chkCreateRoomData($data)) return false;

        $result = $this->save($data,true);
        return $result;
    }

    /**
     * ルーム作成時のdataチェック
     *
     * @param [array] $data
     * @return boolean
     */
    private function chkCreateRoomData($data) {
        if (!$data) return false;

        if (!$data['create_user_id'] || !$data['room_name']) return false;

        return true;
    }

    /**
     * view用に
     * roomIdからルームの情報と作成者の情報を取得
     *
     * @param [int] $roomId
     * @return array|boolean
     */
    public function getRoomInfoByRoomId($roomId) {
        if (!$roomId || !ctype_digit($roomId)) return false;

        $result = $this->find('first',[
            'contain' => ['User'],
            'conditions' => [
                'Room.room_id' => $roomId,
                'Room.delete_flg' => 0,
                'User.delete_flg' => 0
            ],
            'fields' => ['Room.create_user_id','Room.room_name','Room.image_cnt','Room.created_at','User.nick_name',]
        ]);

        return $result;
    }

    /**
     * roomの存在チェック
     *
     * @param [int] $roomId
     * @return boolean
     */
    public function chkExistByRoomId($roomId) {
        if (!$roomId || !ctype_digit($roomId)) return false;

        $result = $this->find('count',[
            'contain' => ['User'],
            'conditions' => [
                'Room.room_id' => $roomId,
                'Room.delete_flg' => 0,
                'User.delete_flg' => 0
            ]
        ]);

        if ($result == 0) return false;

        return true;
    }


    /**
     * roomの投稿数を取得(view用)
     *
     * @param [int] $roomId
     * @return boolean|int
     */
    public function getImageCntByRoomId($roomId) {
        if (!$roomId || !ctype_digit($roomId)) return false;

        $result = $this->find('first',[
            'contain' => ['User'],
            'conditions' => [
                'Room.room_id' => $roomId,
                'Room.delete_flg' => 0,
                'User.delete_flg' => 0
            ],
            'fields' => ['Room.image_cnt']
        ]);

        if (!$result) return false;

        return (int)$result['Room']['image_cnt'];
    }

    /**
     * 画像投稿時にimage_cntを増加させる(view用)
     *
     * @param [int] $roomId
     * @param [int] $imageCnt
     * @return boolean
     */
    public function updateImageCnt($roomId,$imageCnt) {
        if (!ctype_digit($roomId) || !is_int($imageCnt)) return false;//roomIdは文字列型なのでctype_digitを使う

        $data = [
            'room_id' => $roomId,
            'image_cnt' => $imageCnt,
            'update_at' => date("Y-m-d H:i:s")
        ];

        $param = [
            'validate' => true,
            'fieldList' => [
                'image_cnt',
                'update_at'
            ]
        ];

        $result = $this->save($data,$param);
        return $result;
    }

}