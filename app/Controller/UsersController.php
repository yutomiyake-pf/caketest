<?php 
App::uses('AppController', 'Controller');
App::uses('CakeEmail','Network/Email');

class UsersController extends AppController {

    public $uses = ['Room','Roomimage','User'];
    public function beforeFilter(){

        $this->Auth->allow('user_register','login');
    }

    //ユーザーログイン
    public function login() {

        $this->layout = 'users_login';

        if ($this->request->is('post')) {
            $email = $this->request->data('User.email');
            $password = $this->request->data('User.password');

            if (!$email || !$password) {
                $this->Session->setflash('emailとpasswordを入力してください');
                $this->redirect(['action' => 'login']);
            }
            //passwordをハッシュ化
            $passwordHasher = new SimplePasswordHasher(['hashType' => 'sha256']);
            $password = $passwordHasher->hash($password);

            $userData = $this->User->getUserDataByEmailAndPassword($email,$password);
            if (!$userData) {
                $this->Session->setFlash('メールアドレスとパスワードが不正です');
                $this->redirect(['action' => 'login']);
            }

            //セッションに保存
            $this->Auth->login([
                'id' => $userData['User']['user_id'],
                'real_name' => $userData['User']['real_name'],
                'nick_name' => $userData['User']['nick_name'],
                'email' => $userData['User']['email'],
                'image' => $userData['User']['image'],
            ]);

            return $this->redirect([
                'controller' => 'Rooms',
                'action' => 'roomIndex'
            ]);
        }

    }

    //ユーザー登録
    public function user_register() {

        $this->layout = "users_login";

        if ($this->request->is('post')) {
            $this->User->set($this->request->data);

            if ($this->User->validates()) {
                $realName = $this->request->data('User.real_name');
                $nickName = $this->request->data('User.nick_name');
                $email = $this->request->data('User.email');
                $password = $this->request->data('User.password');
                $image = $this->request->data('User.image');
                $now = date("Y-m-d H:i:s");

                //ユーザーの画像をアップロード
                $newImageName = date('YmdHis') . $image['name'];
                $path = WWW_ROOT . "img/UsersImage/";

                if (!move_uploaded_file($image['tmp_name'], $path . $newImageName)) {
                    $this->Session->setFlash('画像の登録に失敗しました。もう一度やり直してください');
                    return redirect(['action' => 'login']);
                }

                //データベースにユーザー情報を格納
                $data = [
                    'real_name' => $realName,
                    'nick_name' => $nickName,
                    'email' => $email,
                    'password' => $password,
                    'image' => $newImageName,
                    'created_at' => $now,
                ];

                try {
                    if (!$this->User->save($data, false)) {
                        throw new InternalErrorException('ユーザー登録が正常に行われませんでした。');
                    }
                } catch (Exception $e) {
                    throw new InternalErrorException('ユーザー登録が正常に行われませんでした。');
                }

                $this->Session->setFlash('登録が完了しました。ログインしてね！');
                return $this->redirect(['action' => 'login']);
            }
        }
    }

    public function account($userId) {

        $path = Router::url();

        debug($path);exit;
    }

    public function mypage($userId) {
        debug($userId);exit;
    }
}