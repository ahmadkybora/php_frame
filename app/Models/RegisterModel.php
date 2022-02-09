<?php
namespace App\Models;

use App\Services\Model;

class RegisterModel extends Model{
    public string $first_name;
    public string $last_name;
    public string $email;
    public string $password;
    public string $password_confirm;

    public function register() {}

    public function rules(): array
    {
        return [
            'first_name' => [self::RULE_REQUIRED], 
            'last_name' => [self::RULE_REQUIRED], 
            'email' => [self::RULE_REQUIRED], 
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 25]], 
            'password_confirm' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']], 
        ];
    }
}