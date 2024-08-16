<?php

namespace App\Helpers\Validator;

use App\Language\Language;
use SplFileObject;
use Psr\Http\Message\UploadedFileInterface;

/**
 * @SuppressWarnings(PHPMD)
 * @phpstan-consistent-constructor
 */
class Validator
{
    use ValidatorRules;

    /**
     * custom validation type
     * @var array
     */
    protected static array $type = [
        'unique' => 'App\Helpers\Validator\Rules\UniqueRule::validate'
    ];

    /**
     * Validation type alias
     * @var array
     */
    protected array $alias = [
        '>' => 'gt', '>=' => 'gte', '<' => 'lt', '<=' => 'lte', '=' => 'eq', 'same' => 'eq',
    ];

    /**
     * current validation rules
     * @var array
     */
    protected array $rule = [];

    /**
     * Verification prompt information
     * @var array
     */
    protected array $message = [];

    /**
     * Validation field description
     * @var array
     */
    protected array $field = [];

    /**
     * Default rule prompt
     * @var array
     */
    protected static array $typeMsg = [];

    /**
     * Current Verification Scenario
     * @var array
     */
    protected $currentScene = null;

    /**
     * Built-in regular validation rules
     * @var array
     */
    protected array $regex = [
        'alpha' => '/^[A-Za-z]+$/',
        'alphaNum' => '/^[A-Za-z0-9]+$/',
        'alphaDash' => '/^[A-Za-z0-9\-\_]+$/',
        'mobileRu' => '/^79[0-9]\d{9}$/',
        'uuid' => '/^[\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12}$/',
    ];

    /**
     * Filter_var rule
     * @var array
     */
    protected array $filter = [
        'email' => FILTER_VALIDATE_EMAIL,
        'ip' => [FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6],
        'integer' => FILTER_VALIDATE_INT,
        'url' => FILTER_VALIDATE_URL,
        'macAddr' => FILTER_VALIDATE_MAC,
        'float' => FILTER_VALIDATE_FLOAT,
    ];

    /**
     * Validation Scenario Definition
     * @var array
     */
    protected array $scene = [];

    /**
     * Authentication failure error message
     * @var array
     */
    protected array $error = [];

    /**
     * Whether batch verification
     * @var bool
     */
    protected bool $batch = false;

    /**
     * Scenario needs to verify the rules
     * @var array
     */
    protected array $only = [];

    /**
     * Validation rules that need to be removed by the scene
     * @var array
     */
    protected array $remove = [];

    /**
     * Scenarios require additional validation rules
     * @var array
     */
    protected array $append = [];

    /**
     * Singleton instance of Language
     * @var Language
     */
    protected static Language $language;

    /**
     * @access public
     * @param array $rules validation rules
     * @param array $message Verification prompt information
     * @param array $field Verify field description information
     */
    public function __construct(
        array $rules = [],
        $message = [],
        $field = []
    ) {
        $this->rule = $rules + $this->rule;
        $this->message = array_merge($this->message, $message);
        $this->field = array_merge($this->field, $field);

        if (!isset(self::$language)) {
            self::$language = Language::getInstance();
        }
    }

    /**
     * Create a validator class
     * @access public
     * @param array $rules validation rules
     * @param array $message Verification prompt information
     * @param array $field Verify field description information
     */
    public static function make($rules = [], $message = [], $field = [])
    {
        return new self($rules, $message, $field);
    }

    /**
     * Add field validation rules
     * @access protected
     * @param string|array  $name  field name or array of rules
     * @param mixed         $rule  Validation rules or field description information
     * @return $this
     */
    public function rule($name, $rule = '')
    {
        if (is_array($name)) {
            $this->rule = $name + $this->rule;
            if (is_array($rule)) {
                $this->field = array_merge($this->field, $rule);
            }
            return $this;
        }

        $this->rule[$name] = $rule;

        return $this;
    }

    /**
     * Получение списка правил
     * @return array
     */
    public function getRules(): array
    {
        return $this->rule;
    }

    /**
     * Register Extended Validation (Type) Rules
     * @access public
     * @param string    $type  validation rule type
     * @param mixed     $callback callback method (or closure)
     * @return void
     */
    public static function extend($type, $callback = null)
    {
        if (is_array($type)) {
            self::$type = array_merge(self::$type, $type);
        } else {
            self::$type[$type] = $callback;
        }
    }

    /**
     * Set the default prompt information for validation rules
     * @access public
     * @param string|array  $type  Validation rule type name or array
     * @param string        $msg  Verification prompt information
     * @return void
     */
    public static function setTypeMsg($type, $msg = null)
    {
        if (is_array($type)) {
            self::$typeMsg = array_merge(self::$typeMsg, $type);
        } else {
            self::$typeMsg[$type] = $msg;
        }
    }

    /**
     * Set reminder information
     * @access public
     * @param string|array  $name  Field Name
     * @param string        $message prompt information
     * @return $this
     */
    public function message($name, $message = '')
    {
        if (is_array($name)) {
            $this->message = array_merge($this->message, $name);
        } else {
            $this->message[$name] = $message;
        }

        return $this;
    }

    /**
     * Set the verification scene
     * @access public
     * @param string  $name  scene name
     * @return $this
     */
    public function scene($name)
    {
        // set current scene
        $this->currentScene = $name;

        return $this;
    }

    /**
     * Determine whether there is a verification scenario
     * @access public
     * @param string $name scene name
     * @return bool
     */
    public function hasScene($name)
    {
        return isset($this->scene[$name]) || method_exists($this, 'scene' . $name);
    }

    /**
     * Set up bulk verification
     * @access public
     * @param bool $batch  Whether batch verification
     * @return $this
     */
    public function batch($batch = true)
    {
        $this->batch = $batch;

        return $this;
    }

    /**
     * Specify the list of fields that need to be validated
     * @access public
     * @param array $fields  field name
     * @return $this
     */
    public function only($fields)
    {
        $this->only = $fields;

        return $this;
    }

    /**
     * Remove validation rules for a field
     * @access public
     * @param string|array  $field  field name
     * @param mixed         $rule   validate_rules true remove all rules
     * @return $this
     */
    public function remove($field, $rule = true)
    {
        if (is_array($field)) {
            foreach ($field as $key => $rule) {
                if (is_int($key)) {
                    $this->remove($rule);
                } else {
                    $this->remove($key, $rule);
                }
            }
        } else {
            if (is_string($rule)) {
                $rule = explode('|', $rule);
            }

            $this->remove[$field] = $rule;
        }

        return $this;
    }

    /**
     * Append a validation rule for a field
     * @access public
     * @param string|array  $field  field name
     * @param mixed         $rule   validation rules
     * @return $this
     */
    public function append($field, $rule = null)
    {
        if (is_array($field)) {
            foreach ($field as $key => $rule) {
                $this->append($key, $rule);
            }
        } else {
            if (is_string($rule)) {
                $rule = explode('|', $rule);
            }

            $this->append[$field] = $rule;
        }

        return $this;
    }

    /**
     * Data Automatic Validation
     * @access public
     * @param array     $data  data
     * @param mixed     $rules  validation rules
     * @param string    $scene Verification scenario
     * @return bool
     */
    public function check($data, $rules = [], $scene = '')
    {
        $this->error = [];

        if (empty($rules)) {
            // read validation rules
            $rules = $this->rule;
        }

        // get scene definition
        $this->getScene($scene);

        foreach ($this->append as $key => $rule) {
            if (!isset($rules[$key])) {
                $rules[$key] = $rule;
            }
        }

        foreach ($rules as $key => $rule) {
            // field => 'rule1|rule2...' field => ['rule1','rule2',...]
            if (strpos($key, '|')) {
                // field|description+ is used to specify the attribute name
                [$key, $title] = explode('|', $key);
            } else {
                $title = isset($this->field[$key]) ? $this->field[$key] : $key;
            }

            // scene detection
            if (!empty($this->only) && !in_array($key, $this->only)) {
                continue;
            }

            // Get data + support two-dimensional array
            $value = $this->getDataValue($data, $key);

            // field validation
            if ($rule instanceof \Closure) {
                // Anonymous function verification + support to pass in two data of current field and all fields
                $result = call_user_func_array($rule, [$value, $data]);
            } elseif ($rule instanceof ValidatorRule) {
                //  verification factor
                $result = $this->checkItem(
                    $key,
                    $value,
                    $rule->getRule(),
                    $data,
                    $rule->getTitle() ?: $title,
                    $rule->getMsg()
                );
            } else {
                $result = $this->checkItem($key, $value, $rule, $data, $title);
            }

            if (true !== $result) {
                // If it does not return true, it means that the verification failed
                if (!empty($this->batch)) {
                    // batch verification
                    if (is_array($result)) {
                        $this->error = array_merge($this->error, $result);
                    } else {
                        $this->error[$key] = $result;
                    }
                } else {
                    $this->error[] = $result;
                    return false;
                }
            }
        }

        return !!empty($this->error);
    }

    /**
     * Validate data against validation rules
     * @access public
     * @param  mixed     $value field value
     * @param  mixed     $rules validation rules
     * @return bool
     */
    public function checkRule($value, $rules)
    {
        if ($rules instanceof \Closure) {
            return call_user_func_array($rules, [$value]);
        } elseif ($rules instanceof ValidatorRule) {
            $rules = $rules->getRule();
        } elseif (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        foreach ($rules as $key => $rule) {
            if ($rule instanceof \Closure) {
                $result = call_user_func_array($rule, [$value]);
            } else {
                // Judgment verification type
                [$type, $rule] = $this->getValidateType($key, $rule);

                $callback = isset(self::$type[$type]) ? self::$type[$type] : [$this, $type];

                $result = call_user_func_array($callback, [$value, $rule]);
            }

            if (true !== $result) {
                return $result;
            }
        }

        return true;
    }

    /**
     * Validate that field values bare in a valid format
     * @access public
     * @param mixed     $value  field value
     * @param string    $rule  validation rules
     * @param array     $data  verify the data
     * @return bool
     */
    public function is($value, $rule, $data = [])
    {
        $rule = preg_replace_callback('/_([a-zA-Z])/', fn ($match) => strtoupper($match[1]), $rule);

        switch (lcfirst($rule)) {
            case 'require':
            case 'required':
                // must
                $result = !empty($value) || '0' == $value;
                break;
            case 'accepted':
                // accept
                $result = in_array($value, ['1', 'on', 'yes']);
                break;
            case 'date':
                // Is it a valid date
                $result = false !== strtotime($value);
                break;
            case 'activeUrl':
                // Is it a valid URL
                $result = checkdnsrr($value);
                break;
            case 'boolean':
            case 'bool':
                // is boolean
                $result = in_array($value, [true, false, 0, 1, '0', '1'], true);
                break;
            case 'number':
                $result = is_numeric($value);
                break;
            case 'string':
                $result = is_string($value);
                break;
            case 'array':
                // Is it an array
                $result = is_array($value);
                break;
            case 'file':
                $result = false;

                // if ($value instanceof UploadedFileInterface) {
                //     $extension = strtolower(pathinfo($value->getClientFilename(), PATHINFO_EXTENSION));
                //     $result = in_array(
                //         $extension,
                //         config('storage.file.allowed_ext')
                //     );
                // } elseif ($value instanceof SplFileObject) {
                //     $extension = strtolower(pathinfo($value->getfilename(), PATHINFO_EXTENSION));

                //     $result = in_array(
                //         $extension,
                //         config('storage.file.allowed_ext')
                //     );
                // }

                break;
            case 'image':
                $result = false;

                // if ($value instanceof UploadedFileInterface) {
                //     $extension = strtolower(pathinfo($value->getClientFilename(), PATHINFO_EXTENSION));
                //     $result = in_array(
                //         $extension,
                //         config('storage.image.allowed_ext')
                //     );
                // } elseif ($value instanceof SplFileObject) {
                //     $result = in_array(
                //         $this->getImageType($value->getRealPath()),
                //         [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP, IMAGETYPE_WEBP]
                //     );
                // }

                break;
            default:
                if (isset(self::$type[$rule])) {
                    // Registered Validation Rules
                    $result = call_user_func_array(self::$type[$rule], [$value, null, $data, null, null]);
                } elseif (isset($this->filter[$rule])) {
                    // Filter_var validation rules
                    $result = $this->filter($value, $this->filter[$rule]);
                } else {
                    // regular validation
                    $result = $this->regex($value, $rule);
                }
        }

        return $result;
    }

    /**
     * Get the current verification type and rules
     * @access public
     * @param  mixed     $key
     * @param  mixed     $rule
     * @return array
     */
    protected function getValidateType($key, $rule)
    {
        // Judgment verification type
        if (!is_numeric($key)) {
            return [$key, $rule, $key];
        }

        if (strpos($rule, ':')) {
            [$type, $rule] = explode(':', $rule, 2);
            if (isset($this->alias[$type])) {
                // Judgment alias
                $type = $this->alias[$type];
            }
            $info = $type;
        } elseif (method_exists($this, $rule)) {
            $type = $rule;
            $info = $rule;
            $rule = '';
        } else {
            $type = 'is';
            $info = $rule;
        }

        return [$type, $rule, $info];
    }

    /**
     * Validate individual field rules
     * @access protected
     * @param string    $field  field name
     * @param mixed     $value  field value
     * @param mixed     $rules  validation rules
     * @param array     $data  data
     * @param string    $title  field description
     * @param array     $msg  prompt information
     * @return mixed
     */
    protected function checkItem($field, $value, $rules, $data, $title = '', $msg = [])
    {
        if (isset($this->remove[$field]) && true === $this->remove[$field] && empty($this->append[$field])) {
            // Field removed + no validation required
            return true;
        }

        // Support multi-rule verification require|in:a,b,c|... or ['require','in'=>'a,b,c',...]
        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        if (isset($this->append[$field])) {
            // Append additional validation rules
            $rules = array_merge($rules, $this->append[$field]);
        }

        $i = 0;
        foreach ($rules as $key => $rule) {
            if ($rule instanceof \Closure) {
                $result = call_user_func_array($rule, [$value, $data]);
                $info = is_numeric($key) ? '' : $key;
            } else {
                // Judgment verification type
                [$type, $rule, $info] = $this->getValidateType($key, $rule);

                if (isset($this->append[$field]) && in_array($info, $this->append[$field])) {
                } elseif (isset($this->remove[$field]) && in_array($info, $this->remove[$field])) {
                    // rule has been removed
                    $i++;
                    continue;
                }

                if ('must' == $info || 0 === strpos($info, 'require') || (!is_null($value) && '' !== $value)) {
                    // authentication type
                    $callback = isset(self::$type[$type]) ? self::$type[$type] : [$this, $type];
                    // verify the data
                    $result = call_user_func_array($callback, [$value, $rule, $data, $field, $title]);
                } else {
                    $result = true;
                }
            }

            if (false === $result) {
                // Verification failed + return error message
                $message = !empty($msg[$i]) ? $msg[$i] : $this->getRuleMsg($field, $title, $info, $rule);

                return $message;
            } elseif (true !== $result) {
                // return custom error message
                if (is_string($result) && false !== strpos($result, ':')) {
                    $result = str_replace(
                        [':attribute', ':rule'],
                        [$title, (string) $rule],
                        $result
                    );
                }

                return $result;
            }
            $i++;
        }

        return $result;
    }

    // get error message
    public function getError()
    {
        return $this->error;
    }

    /**
     * get data value
     * @access protected
     * @param array     $data  data
     * @param string    $key  Data identification + support two-dimensional
     * @return mixed
     */
    protected function getDataValue($data, $key)
    {
        if (is_numeric($key)) {
            $value = $key;
        } elseif (strpos($key, '.')) {
            // Support for two-dimensional array validation
            [$name1, $name2] = explode('.', $key);
            $value = isset($data[$name1][$name2]) ? $data[$name1][$name2] : null;
        } else {
            $value = isset($data[$key]) ? $data[$key] : null;
        }

        return $value;
    }

    /**
     * Get the error message of the validation rule
     * @access protected
     * @param string    $attribute  Field English name
     * @param string    $title  field description name
     * @param string    $type  Validation rule name
     * @param mixed     $rule  validation rule data
     * @return string
     */
    protected function getRuleMsg($attribute, $title, $type, $rule)
    {
        if (isset($this->message[$attribute . '.' . $type])) {
            $msg = $this->message[$attribute . '.' . $type];
        } elseif (isset($this->message[$attribute][$type])) {
            $msg = $this->message[$attribute][$type];
        } elseif (isset($this->message[$attribute])) {
            $msg = $this->message[$attribute];
        } elseif (isset(self::$typeMsg[$type])) {
            $msg = self::$typeMsg[$type];
        } elseif (0 === strpos($type, 'require')) {
            $msg = self::$typeMsg['require'] ?? self::$language->getLine('validation.require');
        } else {
            $translated = self::$language->getLine("validation.{$type}");
            $msg = $translated != "validation.{$type}" ? $translated : self::$typeMsg['default'];
        }

        if (is_string($msg) && is_scalar($rule) && false !== strpos($msg, ':')) {
            // variable substitution
            $array = is_string($rule) && strpos($rule, ',')
                ?
                array_pad(explode(',', $rule), 3, '')
                :
                array_pad([], 3, '');

            $rule = $this->field[$rule] ?? $rule;
            $msg = str_replace(
                [':attribute', ':rule', ':1', ':2', ':3'],
                [$title, (string) $rule, $array[0], $array[1], $array[2]],
                $msg
            );
        }

        return $msg;
    }

    /**
     * Scenarios for obtaining data verification
     * @access protected
     * @param string $scene  Verification scenario
     * @return void
     */
    protected function getScene($scene = '')
    {
        if (empty($scene)) {
            // Read the specified scene
            $scene = $this->currentScene;
        }

        if (empty($scene)) {
            return;
        }

        $this->only = $this->append = $this->remove = [];

        if (method_exists($this, 'scene' . $scene)) {
            call_user_func([$this, 'scene' . $scene]);
        } elseif (isset($this->scene[$scene])) {
            // If the verification applicable scene is set
            $scene = $this->scene[$scene];

            if (is_string($scene)) {
                $scene = explode(',', $scene);
            }

            $this->only = $scene;
        }
    }

    /**
     * Dynamic method + directly call the is method for verification
     * @access protected
     * @param string $method  method name
     * @param array $args  call parameters
     * @return bool
     */
    public function __call($method, $args)
    {
        if ('is' == strtolower(substr($method, 0, 2))) {
            $method = substr($method, 2);
        }

        $args[] = lcfirst($method);

        return call_user_func_array([$this, 'is'], $args);
    }

    /**
     * @param $method
     * @param $args
     * @return false|mixed
     */
    public static function __callStatic($method, $args)
    {
        $validator = new static();

        return call_user_func_array([$validator, $method], $args);
    }

    public function getValidatedData($data): array
    {
        $data = is_array($data) ? $data : [];

        return array_intersect_key($data, $this->getRules());
    }
}
