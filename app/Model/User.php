<?php

App::uses('AppModel','Model');

class User extends AppModel {

    public $useTable = "users";
    public $primaryKey = 'user_id';

    public $validate = [
        
        'real_name' => [
            [
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'お名前を入力してください'
            ],
            [
                'rule' => ['maxLength', 30],
                'message' => '30文字以内で入力してください'
            ]
        ],
        'nick_name' => [
            [
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'ニックネームを入力してください'
            ],
            [
                'rule' => ['maxLength', 20],
                'message' => '20文字以内で入力してください'
            ]
        ],
        'email' => [
            [
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'メールアドレスを入力してください'
            ],
            [
                'rule' => 'email',
                'message' => '正しいメールアドレスを入力してください'
            ],
            [
                'rule' => 'isUnique',
                'message' => 'このメールアドレスは使われています'
            ],
            [
                'rule' => ['minLength',4],
                'message' => '4文字以上で入力してください'
            ],
            [
                'rule' => ['maxLength',40],
                'message' => '40文字以内で入力してください'
            ]
        ],
        'password' => [
            [
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'パスワードを入力してください'
            ],
            [
                'rule' => ['minLength',4],
                'message' => '4文字以上で入力してください'
            ],
            [
                'rule' => ['maxLength',20],
                'message' => '20文字以内で入力してください'
            ],
            [
                'rule' => '/^[a-z0-9]{4,}$/i',
                'message' => '半角英数字しか使えません'
            ],
        ],
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

    //passwordハッシュ化
    public function beforeSave($options = []){

        if(!empty($this->data[$this->alias]['password'])){

            $passwordHasher = new SimplePasswordHasher(['hashType' => 'sha256']);
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
    }

    //ユーザー情報を取得する（email&password）
    public function getUserDataByEmailAndPassword($email,$password) {
        
        $result = $this->find('first',['conditions' => ['email' => $email, 'password' => $password, 'delete_flg' => 0]]);

        return $result;
    }

    //emailからユーザー情報取得
    public function getByEmail($email) {

        $result = $this->find('first',['conditions' => ['email' => $email,'delete_flg' => 0]]);

        return $result;
    }
}