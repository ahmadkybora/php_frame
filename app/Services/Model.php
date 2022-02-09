<?php
namespace App\Services;

abstract class Model {

    // اعتبار سنجی فیلدها
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';

    public function loadData($data)
    {
        foreach($data as $key => $value) {
            if(property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    // متدهای ابسترکت را نمیتوان بدنه نویسی گرد
    // بلکه در کلاس فرزند میتوان برای آنها بدنه نوشت
    abstract public function rules(): array;
    // متد فوق برای اعتبار سنجی فیلدها میباشد

    public array $errors = [];

    public function validate()
    {
        foreach($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach($rules as $rule) {
                $ruleName = $rule;
                if(!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }

                if($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addError($attribute, self::RULE_REQUIRED);
                }

                if($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_SANITIZE_EMAIL)) {
                    $this->addError($attribute, self::RULE_EMAIL);
                }

                if($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addError($attribute, self::RULE_MIN, $rule);
                }

                if($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addError($attribute, self::RULE_MAX, $rule);
                }

                if($ruleName === self::RULE_MATCH && strlen($value) > $rule['max']) {
                    $this->addError($attribute, self::RULE_MATCH, $rule);
                }
            }
        }

        return empty($this->errors);
    }

    public function addError(string $attribute, string $rule, $params = [])
    {
        // در این قسمت جایگزین میکنیم 
        // مقادیر مورد نیاز را با هم
        // ینجا در اصل جستجوی در داخل آرایه میکند و اگه مقدابا
        // بدست آمده با
        // سمبل مورد نظر ما
        // یکی باشد جایگزین میکند
        $message = $this->errorMessage()[$rule] ?? '';
        foreach($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }

        $this->errors[$attribute][] = $message;
    }

    public function errorMessage()
    {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be valid email address',
            self::RULE_MIN => 'Min length of this field must be {min}',
            self::RULE_MAX => 'Max length of this field must be {max}',
            self::RULE_MATCH => 'This field must be the same as {match}',
        ];
    }
}