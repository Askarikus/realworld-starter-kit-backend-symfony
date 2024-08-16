<?php

namespace App\Helpers\Validator;

use Psr\Http\Message\UploadedFileInterface;
use SplFileObject;
use SplFileInfo;

/**
 * @SuppressWarnings(PHPMD)
 */
trait ValidatorRules
{
    /**
     * Verify that it is consistent with the value of a field
     * @access public
     * @param mixed     $value field value
     * @param mixed     $rule  validation rules
     * @param array     $data  data
     * @param string    $field field name
     * @return bool
     */
    public function confirm($value, $rule, $data = [], $field = '')
    {
        if ('' == $rule) {
            $rule = strpos($field, '_confirm') ? strstr($field, '_confirm', true) : $field . '_confirm';
        }

        return $this->getDataValue($data, $rule) === $value;
    }

    /**
     * Verify whether it is different from the value of a field
     * @access public
     * @param mixed $value field value
     * @param mixed $rule  validation rules
     * @param array $data  data
     * @return bool
     */
    public function different($value, $rule, $data = [])
    {
        return $this->getDataValue($data, $rule) != $value;
    }

    /**
     * Verify if greater than or equal to a certain value
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @param array     $data  data
     * @return bool
     */
    public function gte($value, $rule, $data = [])
    {
        return $value >= $this->getDataValue($data, $rule);
    }

    /**
     * Verify if it is greater than a certain value
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @param array     $data  data
     * @return bool
     */
    public function gt($value, $rule, $data)
    {
        return $value > $this->getDataValue($data, $rule);
    }

    /**
     * Verify if it is less than or equal to a value
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @param array     $data  data
     * @return bool
     */
    public function lte($value, $rule, $data = [])
    {
        return $value <= $this->getDataValue($data, $rule);
    }

    /**
     * Verify if it is less than a certain value
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @param array     $data  data
     * @return bool
     */
    public function lt($value, $rule, $data = [])
    {
        return $value < $this->getDataValue($data, $rule);
    }

    /**
     * Verify that it is equal to a value
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @return bool
     */
    public function eq($value, $rule)
    {
        return $value == $rule;
    }

    /**
     * must be verified
     * @access public
     * @param mixed     $value  field value
     * @return bool
     */
    public function must($value)
    {
        return !empty($value) || '0' == $value;
    }

    // Determine image type
    protected function getImageType($image)
    {
        if (function_exists('exif_imagetype')) {
            return exif_imagetype($image);
        }

        try {
            $info = getimagesize($image);
            return $info ? $info[2] : false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Verify whether it is a qualified domain name or IP
     * supports A, MX, NS, SOA, PTR, CNAME, AAAA, A6, SRV, NAPTR, TXT or ANY types
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @return bool
     */
    public function activeUrl($value, $rule = 'MX')
    {
        if (!in_array($rule, ['A', 'MX', 'NS', 'SOA', 'PTR', 'CNAME', 'AAAA', 'A6', 'SRV', 'NAPTR', 'TXT', 'ANY'])) {
            $rule = 'MX';
        }

        return checkdnsrr($value, $rule);
    }

    /**
     * ValidateIP
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules ipv4 ipv6
     * @return bool
     */
    public function ip($value, $rule = 'ipv4')
    {
        if (!in_array($rule, ['ipv4', 'ipv6'])) {
            $rule = 'ipv4';
        }

        return $this->filter($value, [FILTER_VALIDATE_IP, 'ipv6' == $rule ? FILTER_FLAG_IPV6 : FILTER_FLAG_IPV4]);
    }

    /**
     * Detect upload file suffix
     * @param SplFileObject|UploadedFileInterface     $file  upload files
     * @param  array|string     $ext    allow suffix
     * @return bool
     */
    protected function checkExt($file, $ext)
    {
        // if ($file instanceof UploadedFileInterface) {
        //     $extension = strtolower(pathinfo($file->getClientFilename(), PATHINFO_EXTENSION));
        // } else {
        //     $extension = strtolower(pathinfo($file->getfilename(), PATHINFO_EXTENSION));
        // }

        // if (is_string($ext)) {
        //     $ext = explode(',', $ext);
        // }

        // return (bool) in_array($extension, $ext);
    }

    /**
     * Verify uploaded file suffix
     * @access public
     * @param SplFileObject|UploadedFileInterface     $file  upload files
     * @param mixed             $rule  validation rules
     * @return bool
     */
    public function fileExt($file, $rule)
    {
        // if (!($file instanceof UploadedFileInterface) && !($file instanceof SplFileObject)) {
        //     return false;
        // }

        // return $this->checkExt($file, $rule);
    }

    /**
     * Get file type information
     * @param SplFileObject|UploadedFileInterface     $file  upload files
     * @return string
     */
    protected function getMime($file)
    {
        // if ($file instanceof UploadedFileInterface) {
        //     return $file->getClientMediaType();
        // }

        // $finfo = finfo_open(FILEINFO_MIME_TYPE);
        // return finfo_file($finfo, $file->getRealPath() ?: $file->getPathname());
    }

    /**
     * Detect upload file type
     * @param SplFileObject|UploadedFileInterface     $file  upload files
     * @param  array|string     $mime    allowed type
     * @return bool
     */
    protected function checkMime($file, $mime)
    {
        if (is_string($mime)) {
            $mime = explode(',', $mime);
        }

        return (bool) in_array(strtolower($this->getMime($file)), $mime);
    }

    /**
     * Verify upload file type
     * @access public
     * @param SplFileObject|UploadedFileInterface     $file  upload files
     * @param mixed             $rule  validation rules
     * @return bool
     */
    public function fileMime($file, $rule)
    {
        // if (!($file instanceof UploadedFileInterface) && !($file instanceof SplFileObject)) {
        //     return false;
        // }

        // return $this->checkMime($file, $rule);
    }

    /**
     * Verify upload file size
     * @access public
     * @param SplFileObject|UploadedFileInterface     $file  upload files
     * @param mixed             $rule  validation rules
     * @return bool
     */
    public function fileSize($file, $rule)
    {
        // if ($file instanceof UploadedFileInterface) {
        //     return $file->getSize() <= $rule;
        // } elseif ($file instanceof SplFileObject) {
        //     return $file->getSize() <= $rule;
        // }

        return false;
    }

    /**
     * Verify the width, height and type of the image
     * @access public
     * @param SplFileObject|UploadedFileInterface     $file  upload files
     * @param mixed             $rule  validation rules
     * @return bool
     */
    public function image($file, $rule)
    {
        // if ($file instanceof UploadedFileInterface) {
        //     if ($rule) {
        //         $rule = explode(',', $rule);

        //         [$width, $height, $type] = getimagesize($file->getFilePath());

        //         if (isset($rule[2])) {
        //             $imageType = strtolower($rule[2]);

        //             if ('jpeg' == $imageType) {
        //                 $imageType = 'jpg';
        //             }

        //             if (image_type_to_extension($type, false) != $imageType) {
        //                 return false;
        //             }
        //         }

        //         [$w, $h] = $rule;

        //         return $w == $width && $h == $height;
        //     }

        //     $fileArr = explode('.', $file->getClientFilename());
        //     return in_array(
        //         end($fileArr),
        //         config('storage.image.allowed_ext')
        //     );
        // } elseif ($file instanceof SplFileInfo) {
        //     if ($rule) {
        //         $rule = explode(',', $rule);

        //         [$width, $height, $type] = getimagesize($file->getRealPath());

        //         if (isset($rule[2])) {
        //             $imageType = strtolower($rule[2]);

        //             if ('jpeg' == $imageType) {
        //                 $imageType = 'jpg';
        //             }

        //             if (image_type_to_extension($type, false) != $imageType) {
        //                 return false;
        //             }
        //         }

        //         [$w, $h] = $rule;

        //         return $w == $width && $h == $height;
        //     }
        //     return in_array($this->getImageType($file->getRealPath()), [1, 2, 3, 6]);
        // }

        return false;
    }

    /**
     * Authentication request type
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @return bool
     */
    public function method($value, $rule)
    {
        return strtoupper($rule) == $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Verify that the time and date conform to the specified format
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @return bool
     */
    public function dateFormat($value, $rule)
    {
        $info = date_parse_from_format($rule, $value);
        return 0 == $info['warning_count'] && 0 == $info['error_count'];
    }

    /**
     * Use filter_var to verify
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @return bool
     */
    public function filter($value, $rule)
    {
        $param = null;

        if (is_string($rule) && strpos($rule, ',')) {
            [$rule, $param] = explode(',', $rule);
        } elseif (is_array($rule)) {
            $param = isset($rule[1]) ? $rule[1] : null;
            $rule = $rule[0];
        }

        return false !== filter_var($value, is_int($rule) ? $rule : filter_id($rule), $param);
    }

    /**
     * When verifying that a field is equal to a certain value, it must
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @param array     $data  data
     * @return bool
     */
    public function requireIf($value, $rule, $data)
    {
        [$field, $val] = explode(',', $rule);

        if ($this->getDataValue($data, $field) == $val) {
            return !empty($value) || '0' == $value;
        }

        return true;
    }

    /**
     * Verify whether a field is required through the callback method
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @param array     $data  data
     * @return bool
     */
    public function requireCallback($value, $rule, $data)
    {
        if (method_exists($this, $rule)) {
            $result = call_user_func_array([$this, $rule], [$value, $data]);
        } else {
            $result = call_user_func_array($rule, [$value, $data]);
        }

        if ($result) {
            return !empty($value) || '0' == $value;
        }

        return true;
    }

    /**
     * Required to verify that a field has a value
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @param array     $data  data
     * @return bool
     */
    public function requireWith($value, $rule, $data)
    {
        $val = $this->getDataValue($data, $rule);

        if (!empty($val)) {
            return !empty($value) || '0' == $value;
        }

        return true;
    }

    /**
     * Verify that it is in range
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @return bool
     */
    public function in($value, $rule)
    {
        $rule = is_array($rule) ? $rule : explode(',', $rule);

        if (is_array($value)) {
            foreach ($value as $val) {
                if (!in_array($val, $rule)) {
                    return false;
                }
            }

            return true;
        }

        return in_array($value, $rule);
    }

    /**
     * Verify that it is not in a range
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @return bool
     */
    public function notIn($value, $rule)
    {
        return !in_array($value, is_array($rule) ? $rule : explode(',', $rule));
    }

    /**
     * between verify the data
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @return bool
     */
    public function between($value, $rule)
    {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }
        [$min, $max] = $rule;

        return $value >= $min && $value <= $max;
    }

    /**
     * Validate data with notbetween
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @return bool
     */
    public function notBetween($value, $rule)
    {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }
        [$min, $max] = $rule;

        return $value < $min || $value > $max;
    }

    /**
     * Verify data length
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @return bool
     */
    public function length($value, $rule)
    {
        if (is_array($value)) {
            $length = count($value);
            // } elseif ($value instanceof SplFileObject || $value instanceof UploadedFileInterface) {
            //     $length = $value->getSize();
        } else {
            $length = mb_strlen((string) $value);
        }

        if (strpos($rule, ',')) {
            // Length interval
            [$min, $max] = explode(',', $rule);
            return $length >= $min && $length <= $max;
        }

        // specified length
        return $length == $rule;
    }

    /**
     * Validate the maximum length of the data
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @return bool
     */
    public function max($value, $rule)
    {
        if (is_array($value)) {
            $length = count($value);
            // } elseif ($value instanceof SplFileObject || $value instanceof UploadedFileInterface) {
            //     $length = $value->getSize();
        } elseif (is_numeric($value)) {
            $length = $value;
        } else {
            $length = mb_strlen((string) $value);
        }

        return $length <= $rule;
    }

    /**
     * Authentication data minimum length
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @return bool
     */
    public function min($value, $rule)
    {
        if (is_array($value)) {
            $length = count($value);
            // } elseif ($value instanceof SplFileObject || $value instanceof UploadedFileInterface) {
            //     $length = $value->getSize();
        } elseif (is_numeric($value)) {
            $length = $value;
        } else {
            $length = mb_strlen((string) $value);
        }
        return $length >= $rule;
    }

    /**
     * verification date
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @return bool
     */
    public function after($value, $rule)
    {
        return strtotime($value) >= strtotime($rule);
    }

    /**
     * verification date
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @return bool
     */
    public function before($value, $rule)
    {
        return strtotime($value) <= strtotime($rule);
    }

    /**
     * Validation period
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @return bool
     */
    public function expire($value, $rule)
    {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }

        [$start, $end] = $rule;

        if (!is_numeric($start)) {
            $start = strtotime($start);
        }

        if (!is_numeric($end)) {
            $end = strtotime($end);
        }

        if (!is_numeric($value)) {
            $value = strtotime($value);
        }

        return $value >= $start && $value <= $end;
    }

    /**
     * verification phone
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  validation rules
     * @return bool
     */
    public function phone($value, $rule): bool
    {
        $value = preg_replace('/[^\d]/isu', '', $value);

        return mb_strlen($value) >= 10;
    }

    /**
     * Use regex to validate data
     * @access public
     * @param mixed     $value  field value
     * @param mixed     $rule  Validation rules + regular rules or predefined regular names
     * @return mixed
     */
    public function regex($value, $rule)
    {
        if (isset($this->regex[$rule])) {
            $rule = $this->regex[$rule];
        }

        if (0 !== strpos($rule, '/') && !preg_match('/\/[imsU]{0,4}$/', $rule)) {
            // If it is not a regular expression, fill in both ends/
            $rule = '/^' . $rule . '$/';
        }

        return is_scalar($value) && 1 === preg_match($rule, (string) $value);
    }
}
