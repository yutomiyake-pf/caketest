<?php

App::uses('AppModel','Model');

/**
 * 外部キー
 * User.user_id(画像の投稿者) Room.room_id(投稿されたルーム)
 * 
 * アソシエーション
 * User
 */
class Roomimage extends AppModel {

    public $useTable = "roomimages";
    public $primaryKey = 'room_image_id';
    public $actsAs = ['Containable'];

    public $belongsTo = [
        'User' => [
            'foreignKey' => 'user_id', 
        ],
    ];

    public $validate = [
        'image' => [
            [
                'rule' => [
                    'extension',['gif','jpg','jpeg'],
                    'required' => true
                ],
                'message' => '拡張子がgifかjpgかjpegのものにしてください'
            ],
            [
                'rule' => [
                    'mimeType',['image/gif','image/jpg','image/jpeg'],
                ],
                'message' => '画像タイプはgifかjpgかjpegのものを使ってください'
            ],
        ]
    ];

    /**
     * room_index用の写真を取得する(room_id=>0)
     * コントローラでpaginateしているので使わない
     * recursive(0)
     * @return array $result
     */
    // public function getRoomIndexImages() {
    //     $result = $this->find('all',[
    //         'recursive' => 0,
    //         'conditions' => [
    //             'Roomimage.room_id' => 0,
    //             'Roomimage.delete_flg' => 0,
    //             'User.delete_flg' => 0
    //         ],
    //     ]);
    //     return $result;
    // } 

    /**
     * ルーム写真id(room_image_id)から投稿者のuser_idを取得する
     * 
     *
     * @param [int] $roomImageId
     * @return array
     */
    public function getUserIdByRoomImageId($roomImageId) {
        if (!$roomImageId) return false;

        $result = $this->find('first',[
            'conditions' => [
                'room_image_id' => $roomImageId,
                'delete_flg' => 0,
            ],
            'fields' => [
                'user_id'
            ]

        ]);

        return $result;
    }

    /**
     * 画像の削除
     *
     * @param [int] $roomImageId
     * @return boolean
     */
    public function deleteImageByRoomImageId($roomImageId) {
        if (!$roomImageId) return false;

        $data = [
            'room_image_id' => $roomImageId,
            'delete_flg' => 1,
            'deleted_at' => date("Y-m-d H:i:s")
        ];

        $param = [
            'validate' => false,
            'fieldList' => [
                'delete_flg',
                'deleted_at'
            ],
        ];
        $result = $this->save($data,$param);
        return $result;
    }

    /**
     * room_indexの画像数を取得
     *
     * @param [int] $roomId
     * @return int
     */
    public function getIndexImageCntByRoomId($roomId) {

        $result = $this->find('count',[
            'recursive' => 0,
            'conditions' => [
                'Roomimage.room_id' => $roomId,
                'Roomimage.delete_flg' => 0,
                'User.delete_flg' => 0
            ]
        ]);

        return $result;
    }

    /**
     * フィールドインサートメソッド
     *
     * @param [array] $param
     * @return boolean
     */
    public function insertParam($param) {
        if (!$param) return false;

        $result = $this->save($param,false);

        return $result;
    }

    /**
     * ルームの投稿数ランキングを取得
     *
     * @return array
     */
    // public function getRank() {

    //     $allImage = $this->find('all',[
    //             // 'recursive' => 0,
    //             'conditions' => [
    //                 'Roomimage.delete_flg' => 0,
    //                 // 'User.delete_flg' => 0
    //             ]
    //         ]);

    //     $roomParams = [];
    //     foreach ($allImage as $image) {
    //         if (!in_array($image['Roomimage']['room_id'],$roomParams,true)) {
    //             $roomParams[] = $image['Roomimage']['room_id'];
    //         }
    //     }

    //     $countParams = [];
    //     foreach($roomParams as $roomParam) {
    //         $count = $this->find('count',[
    //             // 'recursive' => 0,
    //             'conditions' => [
    //                 'Roomimage.room_id' => $roomParam,
    //                 'Roomimage.delete_flg' => 0,
    //                 // 'User.delete_flg' => 0
    //             ]
    //         ]);
    //         $countParams[] = [
    //             'count' => $count,
    //             'room_id' => $roomParam,
    //         ];
    //     }

    //     arsort($countParams);//降順に並び替え
    //     $result = [];
    //     $i = 1;
    //     foreach ($countParams as $countParam) {
    //         $result[$i] = [
    //             'room_id' => $countParam['room_id'],
    //             'count' => $countParam['count']
    //         ];
    //         $i++;
    //     }
    //     return $result;
    // }

}