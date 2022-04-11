<?php

namespace App\Support;

use Illuminate\Support\Facades\Session;

class Message
{
    public const TYPE_SUCCESS = "success";
    public const TYPE_INFO = "info";
    public const TYPE_WARNING = "warning";
    public const TYPE_ERROR = "error";

    public function __construct(string $message, string $title = null, string $type = self::TYPE_SUCCESS, bool $fixed = false)
    {
        $this->message = $message;
        $this->title = $title;
        $this->type = $type;
        $this->fixed = $fixed;
    }

    public static function success(string $message, string $title = null): Message
    {
        return new Message($message, $title, self::TYPE_SUCCESS, false);
    }

    public static function info(string $message, string $title = null): Message
    {
        return new Message($message, $title, self::TYPE_INFO, false);
    }

    public static function warning(string $message, string $title = null): Message
    {
        return new Message($message, $title, self::TYPE_WARNING, false);
    }

    public static function error(string $message, string $title = null): Message
    {
        return new Message($message, $title, self::TYPE_ERROR, false);
    }

    public function fixed(): Message
    {
        $this->fixed = true;
        return $this;
    }

    public function flash()
    {
        Session::flash($this->type, (object)[
            "title" => $this->title,
            "message" => $this->message,
            "fixed" => $this->fixed,
        ]);
        return true;
    }

    public function get()
    {
        return (object)[
            "style" => $this->defStyle($this->type),
            "title" => $this->title,
            "message" => $this->message,
            "fixed" => $this->fixed,
        ];
    }

    public function defStyle($type)
    {
        switch ($type) {
            case "success":
                return "alert-success";
                break;
            case "info":
                return "alert-info";
                break;
            case "warning":
                return "alert-warning";
                break;
            case "error":
                return "alert-danger";
                break;
            default:
                return "alert-secondary";
        }
    }
}
