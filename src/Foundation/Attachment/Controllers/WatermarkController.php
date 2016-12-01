<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-11-18 15:40
 */
namespace Notadd\Foundation\Attachment\Controllers;

use Notadd\Foundation\Attachment\Handlers\WatermarkSetHandler;
use Notadd\Foundation\Routing\Abstracts\Controller;
use Notadd\Foundation\Setting\Contracts\SettingsRepository;

class WatermarkController extends Controller
{
    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $settings;

    /**
     * WatermarkController constructor.
     *
     * @param \Notadd\Foundation\Setting\Contracts\SettingsRepository $settings
     */
    public function __construct(SettingsRepository $settings)
    {
        parent::__construct();
        $this->settings = $settings;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     */
    public function handle()
    {
        $handler = new WatermarkSetHandler($this->container, $this->settings);
        $response = $handler->toResponse($this->request);

        return $response->generateHttpResponse();
    }
}