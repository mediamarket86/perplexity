<?php
$arComponentParameters = [
    "GROUPS" => [
        'LIMITS' => ['NAME' => GetMessage("PVP_SMS_AUTH_MSG_LIMITS"), 'SORT' => 1]
    ],
    "PARAMETERS" => array(
        "REGISTER_URL" => array(
            "NAME" => GetMessage("PVP_SMS_AUTH_FORM_REGISTER_URL"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ),

        "FORGOT_PASSWORD_URL" => array(
            "NAME" => GetMessage("PVP_SMS_AUTH_AUTH_FORM_FORGOT_PASSWORD_URL"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ),

        "PROFILE_URL" => array(
            "NAME" => GetMessage("PVP_SMS_AUTH_AUTH_FORM_PROFILE_URL"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ),

        "SHOW_ERRORS" => array(
            "NAME" => GetMessage("PVP_SMS_AUTH_AUTH_FORM_SHOW_ERRORS"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
        ),

        "MESSAGE_LIMIT" => array(
            "PARENT" => "LIMITS",
            "NAME" => GetMessage("PVP_SMS_AUTH_MESSAGE_LIMIT"),
            "TYPE" => "STRING",
            "DEFAULT" => "5",
        ),

        "SEND_TIMEOUT" => array(
            "PARENT" => "LIMITS",
            "NAME" => GetMessage("PVP_SMS_AUTH_SEND_TIMEOUT"),
            "TYPE" => "STRING",
            "DEFAULT" => "60",
        ),

        "RESET_LIMIT" => array(
            "PARENT" => "LIMITS",
            "NAME" => GetMessage("PVP_SMS_AUTH_RESET_TIMEOUT"),
            "TYPE" => "STRING",
            "DEFAULT" => "300",
        ),

        "CODE_TTL" => array(
            "PARENT" => "LIMITS",
            "NAME" => GetMessage("PVP_SMS_AUTH_CODE_TTL"),
            "TYPE" => "STRING",
            "DEFAULT" => "300",
        ),
    ),
];