<?php

namespace PVP\SmsAuth\sender;

interface SenderInterface
{
    public function send(string $phone, string $message): bool;
}