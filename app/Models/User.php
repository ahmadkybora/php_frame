<?php
namespace App\Models;

use App\Services\UserModel;

class User extends UserModel
{
    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;
    const STATUS_DELETED = 2;

    public string $first_name = "";
    public string $last_name = "";
    public string $email = "";
    public int $status = self::STATUS_INACTIVE;
    public string $password = "";
    public string $password_confirm = "";

    public function tableName(): string
    {
        return 'users';
    }

    public function rules(): array
    {
        return [
            'first_name' => [self::RULE_REQUIRED], 
            'last_name' => [self::RULE_REQUIRED], 
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [
                self::RULE_UNIQUE, 'class' => self::class
            ]], 
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 25]], 
            'password_confirm' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']], 
        ];
    }

    public function save()
    {
        $this->status = self::STATUS_INACTIVE;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }

    public function primaryKey(): string
    {
        return 'id';
    }
    
    public function attributes(): array
    {
        return [
            'first_name', 
            'last_name', 
            'email', 
            'password', 
            'status', 
        ];
    }

    public function labels(): array
    {
        return [
            'first_name' => 'First Name', 
            'last_name' => 'Last Name', 
            'email' => 'Email', 
            'password' => 'Password', 
            'passwordConfirm' => 'Confirmation Password', 
        ];
    }

    public function getDisplayName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}