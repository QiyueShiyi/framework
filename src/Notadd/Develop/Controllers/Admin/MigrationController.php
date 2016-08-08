<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-02-29 20:35
 */
namespace Notadd\Develop\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Notadd\Foundation\Abstracts\AbstractAdminController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
/**
 * Class MigrationController
 * @package Notadd\Develop\Controllers\Admin
 */
class MigrationController extends AbstractAdminController {
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        $this->share('message', $this->session->get('message'));
        return $this->view('admin::develop.migration');
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $command = $this->getCommand('make:migration');
        $data = new Collection();
        $data->put('name', $request->get('name'));
        if($request->get('create')) {
            $data->put('--create', $request->get('create'));
        } elseif($request->get('table')) {
            $data->put('--table', $request->get('table'));
        }
        $input = new ArrayInput($data->toArray());
        $output = new BufferedOutput();
        $command->run($input, $output);
        return $this->redirect->to('admin/migration')->with('message', $output->fetch());
    }
}