<?php

namespace App\Helpers\Validator;

/**
 * Class ValidateRule
 * @method ValidatorRule confirm(mixed $field, string $msg = '') static
 * @method ValidatorRule different(mixed $field, string $msg = '') static
 * @method ValidatorRule gte(mixed $value, string $msg = '') static
 * @method ValidatorRule gt(mixed $value, string $msg = '') static
 * @method ValidatorRule lte(mixed $value, string $msg = '') static
 * @method ValidatorRule lt(mixed $value, string $msg = '') static
 * @method ValidatorRule eq(mixed $value, string $msg = '') static
 * @method ValidatorRule in(mixed $values, string $msg = '') static
 * @method ValidatorRule notIn(mixed $values, string $msg = '') static
 * @method ValidatorRule between(mixed $values, string $msg = '') static
 * @method ValidatorRule notBetween(mixed $values, string $msg = '') static
 * @method ValidatorRule length(mixed $length, string $msg = '') static
 * @method ValidatorRule max(mixed $max, string $msg = '') static
 * @method ValidatorRule min(mixed $min, string $msg = '') static
 * @method ValidatorRule after(mixed $date, string $msg = '') static
 * @method ValidatorRule before(mixed $date, string $msg = '') static
 * @method ValidatorRule expire(mixed $dates, string $msg = '') static
 * @method ValidatorRule regex(mixed $rule, string $msg = '') static
 * @method ValidatorRule token(mixed $token, string $msg = '') static
 * @method ValidatorRule is(mixed $rule = null, string $msg = '') static
 * @method ValidatorRule isRequire(mixed $rule = null, string $msg = '') static
 * @method ValidatorRule isNumber(mixed $rule = null, string $msg = '') static
 * @method ValidatorRule isArray(mixed $rule = null, string $msg = '') static
 * @method ValidatorRule isInteger(mixed $rule = null, string $msg = '') static
 * @method ValidatorRule isFloat(mixed $rule = null, string $msg = '') static
 * @method ValidatorRule isMobile(mixed $rule = null, string $msg = '') static
 * @method ValidatorRule isIdCard(mixed $rule = null, string $msg = '') static
 * @method ValidatorRule isDate(mixed $rule = null, string $msg = '') static
 * @method ValidatorRule isBool(mixed $rule = null, string $msg = '') static
 * @method ValidatorRule isAlpha(mixed $rule = null, string $msg = '') static
 * @method ValidatorRule isAlphaDash(mixed $rule = null, string $msg = '') static
 * @method ValidatorRule isAlphaNum(mixed $rule = null, string $msg = '') static
 * @method ValidatorRule isAccepted(mixed $rule = null, string $msg = '') static
 * @method ValidatorRule isEmail(mixed $rule = null, string $msg = '') static
 * @method ValidatorRule isUrl(mixed $rule = null, string $msg = '') static
 * @method ValidatorRule activeUrl(mixed $rule = null, string $msg = '') static
 * @method ValidatorRule ip(mixed $rule = null, string $msg = '') static
 * @method ValidatorRule fileExt(mixed $ext, string $msg = '') static
 * @method ValidatorRule fileMime(mixed $mime, string $msg = '') static
 * @method ValidatorRule fileSize(mixed $size, string $msg = '') static
 * @method ValidatorRule image(mixed $rule, string $msg = '') static
 * @method ValidatorRule method(mixed $method, string $msg = '') static
 * @method ValidatorRule dateFormat(mixed $format, string $msg = '') static
 * @method ValidatorRule unique(mixed $rule, string $msg = '') static
 * @method ValidatorRule behavior(mixed $rule, string $msg = '') static
 * @method ValidatorRule filter(mixed $rule, string $msg = '') static
 * @method ValidatorRule requireIf(mixed $rule, string $msg = '') static
 * @method ValidatorRule requireCallback(mixed $rule, string $msg = '') static
 * @method ValidatorRule requireWith(mixed $rule, string $msg = '') static
 * @method ValidatorRule must(mixed $rule = null, string $msg = '') static
 * @method ValidatorRule isUuid(mixed $rule = null, string $msg = '') static
 *
 * @phpstan-consistent-constructor
 */
class ValidatorRule
{
    //Название поля
    protected $title;

    //Список правил для проверки
    protected array $rule = [];

    //Сообщения об ошибках
    protected array $message = [];

    public function __construct()
    {
    }

    /**
     * Add verification factor
     * @access protected
     * @param string    $name  verification name
     * @param mixed     $rule  validation rules
     * @param string    $msg   prompt information
     * @return $this
     */
    protected function addItem($name, $rule = null, $msg = '')
    {
        $this->message[] = $msg;

        if ($rule || 0 === $rule) {
            $this->rule[$name] = $rule;
            return $this;
        }

        $this->rule[] = $name;

        return $this;
    }

    /**
     * Get validation rules
     * @access public
     * @return array
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * Get validation field name
     * @access public
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * get verification prompt
     * @access public
     * @return array
     */
    public function getMsg()
    {
        return $this->message;
    }

    /**
     * Set validation field name
     * @access public
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    public function __call($method, $args)
    {
        if ('is' == strtolower(substr($method, 0, 2))) {
            $method = substr($method, 2);
        }

        array_unshift($args, lcfirst($method));

        return call_user_func_array([$this, 'addItem'], $args);
    }

    public static function __callStatic($method, $args)
    {
        $rule = new static();

        if ('is' == strtolower(substr($method, 0, 2))) {
            $method = substr($method, 2);
        }

        array_unshift($args, lcfirst($method));

        return call_user_func_array([$rule, 'addItem'], $args);
    }
}
