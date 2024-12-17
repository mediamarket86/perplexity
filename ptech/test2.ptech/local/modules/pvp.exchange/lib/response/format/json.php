<?php

namespace PVP\Exchange\Response\Format;

class Json implements FormatterInterface
{
    public function format(\PVP\Exchange\Response\Response $response)
    {
        $data = $response->getResponseData();
        $this->prepareValues($data);

        $result = json_encode($data);

        if ($result) {
            return $result;
        }

        throw new \Exception('JSON convert error: ' . json_last_error() . ' - ' . json_last_error_msg());
    }

    protected function prepareValues(array &$data)
    {
        foreach ($data as &$value) {
            if (is_array($value)) {
                $this->prepareValues($value);
            } else {
                if (is_float($value) && (is_nan($value) || is_infinite($value))) {
                    $value = 0;
                }
            }
        }
    }
}