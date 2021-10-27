<?php 
App::uses('AppController', 'Controller');

class AjaxController extends AppController {

    public $uses = ['Room','Roomimage','User'];

    /**
     * room_index用に投稿写真数を取得
     *
     * @return [int]
     */
    public function getIndexImageCntByRoomId ($roomId) {
        if (!isset($roomId)) return json_encode(0);
        $this->autoRender = false;
        // $this->autoLayout = false;

        $roomImageCnt = $this->Roomimage->getIndexImageCntByRoomId($roomId);
        if (!isset($roomImageCnt)) $roomImageCnt = 0;

        return json_encode($roomImageCnt);
    }
}