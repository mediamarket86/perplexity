<?php
namespace PVP\Exchange\Response\Format;

interface FormatterInterface
{
    public function format(\PVP\Exchange\Response\Response $response);
}