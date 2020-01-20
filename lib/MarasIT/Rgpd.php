<?php
namespace DvkWP\MarasIT;

class Rgpd {

    public static $background = "#333333";
    public static $text = "#ffffff";
    public static $content = [
        "en" => [
            "message" => "This site uses cookies to guarantee you the best experience on our site.",
            "dismiss" => "Seen!",
            "allow" => "Allow cookies",
            "deny" => "Decline",
            "link" => "Learn more",
            "href" => "/cookies-policy",
            "policy" => "Cookies policy",
        ],
        "fr" => [
            "message" => "Ce site utilise des cookies pour vous garantir la meilleure expérience sur notre site.",
            "dismiss" => "Vu !",
            "allow" => "Autorise les cookies",
            "deny" => "Décliner",
            "link" => "En savoir plus",
            "href" => "/politique-de-cookies",
            "policy" => "Politique de cookies",
        ]
    ];

    public function __construct() {

    }

    static public function get($lang = 'en') {

        return [
            "background" => self::$background,
            "text" => self::$text,
            "content" => !empty(self::$content[$lang]) ? self::$content[$lang] : self::$content['en'],
        ];

    }

}
