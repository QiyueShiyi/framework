<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-11-23 15:11
 */
namespace Notadd\Foundation\Passport\Abstracts;

use Exception;
use Notadd\Foundation\Passport\Responses\ApiResponse;

/**
 * Class SetHandler.
 */
abstract class SetHandler extends DataHandler
{
    /**
     * TODO: Method data Description
     *
     * @return array
     */
    public function data()
    {
        return [];
    }

    /**
     * TODO: Method execute Description
     *
     * @return bool
     * @throws \Exception
     */
    public function execute()
    {
        throw new Exception('Method execute is not setted!');
    }

    /**
     * TODO: Method toResponse Description
     *
     * @return \Notadd\Foundation\Passport\Responses\ApiResponse
     */
    public function toResponse()
    {
        $result = $this->execute();
        if ($result) {
            $messages = $this->messages();
        } else {
            $messages = $this->errors();
        }
        $response = new ApiResponse();

        return $response->withParams([
            'code' => $this->code(),
            'data' => $this->data(),
            'message' => $messages,
        ]);
    }
}
