<?php
namespace PVP\ExchangeV1;

interface MethodInterface
{
    const DEBUG = 0;

    public function __construct($param);

    public function execute();

    public function getResultCode():int ;

    public function getResult():array ;
}
