<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class AdminUserRegisteForm extends Model
{
    public $username;
    public $name;
    public $lastname;
    public $email;
    public $password;
    public $custompassword;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            //['password', 'required'],
            //['password', 'string', 'min' => 6],

            [['name', 'lastname', 'custompassword'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'lastname' => 'Login',
            'name' => 'Имя',
            'lastname' => 'Фамилия',
            'custompassword' => 'Пароль',
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->name = $this->name;
        $user->lastname = $this->lastname;
        $user->email = $this->email;
        $user->custompassword = $this->custompassword;
        $user->setPassword($this->custompassword);
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;
        return $user->save(false);
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */

}
