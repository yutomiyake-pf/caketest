<?php 
App::uses('AppController', 'Controller');
App::uses('CakeEmail','Network/Email');

class RoomsController extends AppController {

    public $uses = ['Room','Roomimage','User'];
    //ルーム初期画面(room_idが0のもの)
    public function roomIndex() {

        $this->layout = 'room_index';
        $userId = $this->Auth->user('id');
        $roomId = 0;
        //room_index用の画像を取得
        //3.6.9
        $this->paginate = [
            'Roomimage' => [
                'recursive' => 0,
                'limit' => 36,
                'order' => 'Roomimage.created_at desc'
            ]
        ];
        $roomIndexImages = $this->Paginator->paginate('Roomimage',[
            'Roomimage.room_id' => $roomId,
            'Roomimage.delete_flg' => 0,
            'User.delete_flg' => 0
        ]);

        $this->set(compact('roomIndexImages','userId','roomId'));

        //roomIndexでの投稿は同じメソッド内で処理をする
        if ($this->request->is('post')) {
            $this->Roomimage->set($this->request->data);

            if ($this->Roomimage->validates()) {
                $image = $this->request->data('Roomimage.image');
                $created = date("Y-m-d H:i:s");

                //roomimagesに画像アップロード
                $newImageName = date('YmdHis') . $image['name'];
                $path = WWW_ROOT . "img/RoomsImage/";

                if (!move_uploaded_file($image['tmp_name'], $path . $newImageName)) {
                    $this->Session->setFlash('画像の登録に失敗しました。もう一度やり直してください');
                    return redirect(['action' => 'roomIndex']);
                }

                $postData = [
                    'user_id' => $userId,
                    'room_image' => $newImageName,
                    'created_at' => $created,
                ];

                //ルーム０の画像の投稿はコントローラで行う
                try {
                    if(!$this->Roomimage->save($postData,false)) {
                        throw new InternalErrorException('画像の投稿が正常に行われませんでした。');
                    }
                }catch (Exception $e) {
                    $this->Session->setFlash('画像の投稿が正常に行われませんでした。');
                    return $this->redirect(['action' => 'roomIndex']);
                }

                $this->Session->setFlash('画像が投稿されました');
                return $this->redirect(['action' => 'roomIndex']);
            }
            $this->Session->setFlash('画像に不備があります。ご確認ください。');
        }

    }

    //写真削除(論理削除)＊古いバージョンなので後で修正する
    public function deleteImage($room_image_id) {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException('不正な通信です');
        }

        //ajax通信で削除処理
        if ($this->request->is('ajax')) {
            if (!$room_image_id) {
                $this->Session->setFlash("写真情報が取得できませんでした");
                return $this->redirect(['action' => 'roomIndex']);
            }

            $imageUserId = $this->Roomimage->getUserIdByRoomImageId($room_image_id);
            $userId = $this->Auth->user('id');
            
            if ($imageUserId['Roomimage']['user_id'] !== $userId) {
                $this->Session->setFlash("写真を削除する権限がありません");
                return $this->redirect(['action' => 'roomIndex']);
            }
    
            try {
                if(!$this->Roomimage->deleteImageByRoomImageId($room_image_id)) {
                    throw new InternalErrorException('画像の削除が正常に行われませんでした。');
                }
            }catch (Exception $e) {
                $this->Session->setFlash('画像の削除が正常に行われませんでした。');
                return $this->redirect(['action' => 'roomIndex']);
            }

            $this->autoRender = false;
            $this->autoLayout = false;
            $res = ['id' => $room_image_id];
            echo json_encode($res);
            exit;
        }
        
        return $this->redirect(['action' => 'roomIndex']);
    }

    //room作成
    public function roomCreate() {
        if (!$this->request->is('post')) {
            $this->Session->setFlash('不正な通信です');
            return $this->redirect(['action' => 'roomSearch']);
        }

        if ($this->request->is('post')) {
            $this->Room->set($this->request->data);

            if ($this->Room->validates()) {

                $roomName = $this->request->data('Room.room_name');
                $userId = $this->Auth->user('id');
                $now = date('Y-m-d H:i:s');

                $data = [
                    'room_name' => $roomName,
                    'create_user_id' => $userId,
                    'created_at' => $now
                ];

                try {
                    if (!$this->Room->createRoom($data)) {
                        throw new InternalErrorException('ルームの作成が正常に行われませんでした。');
                    }
                }catch (Exception $e) {
                    $this->Session->setFlash('ルームの作成が正常に行われませんでした。');
                    return $this->redirect(['action' => 'roomSearch']);
                }

                $this->Session->setFlash('ルームが作成されました。');
                return $this->redirect(['action' => 'roomSearch']);
            } else {
                $this->Session->setFlash('入力されたルーム名に不備があります。ご確認ください。');
                return $this->redirect(['action' => 'roomSearch']);
            }
        }
    }

    //room検索
    public function roomSearch() {
        $this->layout = "room_search";

        //キーワード検索
        $keyWord = $this->request->query('keyword');
        if ($keyWord === "") {
            $this->Session->setFlash('キーワードが入力されていません。');
            return $this->redirect(['action' => 'roomSearch']);
        }

        //検索結果をpaginate
        if (isset($keyWord)) {
            $this->paginate = [
                'Room' => [
                    'recursive' => 0,
                    'limit' => 10,
                    'order' => 'Room.created_at desc',
                    'fields' => ['User.user_id','User.nick_name','Room.room_id','Room.room_name','Room.created_at']
                ]
            ];
            $rooms = $this->Paginator->paginate('Room',[
                'or' => [
                    'Room.room_id like' => '%'.$keyWord.'%',
                    'Room.room_name like' => '%'.$keyWord.'%',
                    //'Room.created_at like' => '%'.$keyWord.'%',
                ],
                'User.delete_flg' => 0,
                'Room.delete_flg' => 0
            ]);

            if ($rooms) {
                $this->set(compact('rooms','keyWord'));
            } else {
                $this->Session->setFlash('ルームが見つかりませんでした。');
                return $this->redirect(['action' => 'roomSearch']);
            }
        }
    }

    //room投稿画面(room_idが0以外)
    public function view($roomId) {
        if (!$roomId || !$this->request->is('get') || !ctype_digit($roomId) || !$roomInfo = $this->Room->getRoomInfoByRoomId($roomId)) {
            $this->Session->setFlash('ルームが見つかりませんでした。もう一度検索からやり直してください。');
            return $this->redirect(['action' => 'roomSearch']);
        }

        $this->layout = 'room_view';
        $userId = $this->Auth->user('id');

        //3.6.9
        $this->paginate = [
            'Roomimage' => [
                'recursive' => 0,
                'limit' => 36,
                'order' => 'Roomimage.created_at desc'
            ]
        ];
        $roomImages = $this->Paginator->paginate('Roomimage',[
            'Roomimage.room_id' => $roomId,
            'Roomimage.delete_flg' => 0,
            'User.delete_flg' => 0
        ]);

        $this->set(compact('userId','roomId','roomImages','roomInfo'));
    }

    /**
     * 写真投稿(view用)
     *
     * @param [int] $roomId
     * @return boolean
     */
    public function postViewImage($roomId) {
        if (!$this->request->is('post') || !$roomId || !ctype_digit($roomId) || !$this->Room->chkExistByRoomId($roomId)) {
            $this->Session->setFlash('不正な操作がありました。ルームの検索からやり直してください。');
            return $this->redirect(['action' => 'roomSearch']);
        }

        $this->Roomimage->set($this->request->data);
        if ($this->Roomimage->validates()) {
            $imageCnt = $this->Room->getImageCntByRoomId($roomId);
            if ($imageCnt === false) {
                $this->Session->setFlash('ルームの情報取得に失敗しました。ルームの検索からやり直してください。');
                return $this->redirect(['action' => 'roomSearch']);
            }
            $postImageCnt = ++$imageCnt;
            $image = $this->request->data('Roomimage.image');
            $created = date("Y-m-d H:i:s");
            $userId = $this->Auth->user('id');

            //roomimagesに画像アップロード
            $newImageName = date('YmdHis') . $image['name'];
            $path = WWW_ROOT . "img/RoomsImage/";

            if (!move_uploaded_file($image['tmp_name'], $path . $newImageName)) {
                $this->Session->setFlash('画像の登録に失敗しました。ルームの検索からやり直してください。');
                return redirect(['action' => 'roomSearch']);
            }

            $postImageData = [
                'room_id' => $roomId,
                'user_id' => $userId,
                'room_image' => $newImageName,
                'created_at' => $created,
            ];

            try {
                if (!$this->Roomimage->insertParam($postImageData)) {
                    throw new InternalErrorException('画像の投稿が正常に行われませんでした。');
                }
            } catch (Exception $e) {
                $this->Session->setFlash('画像の投稿が正常に行われませんでした。');
                return $this->redirect(['action' => 'roomSearch']);
            }

            try {
                if (!$this->Room->updateImageCnt($roomId,$postImageCnt)) {
                    throw new InternalErrorException('ルームの画像投稿数が増加できませんでした。');
                } 
            }catch (Exception $e) {
                $this->Session->setFlash('ルームの画像投稿数が増加できませんでした。');
                return $this->redirect(['action' => 'roomSearch']);
            }

            $this->Session->setFlash('画像が投稿されました。');
            return $this->redirect(['action' => 'view',$roomId]);
        }

    }

}