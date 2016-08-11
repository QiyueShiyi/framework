@extends('admin::layouts')
@section('title')网站信息设置@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li class="active"><a href="{{ url('admin/site')}}">网站信息</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                @include('admin::common.messages')
                <div class="col-md-12">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading mb20"><i>网站信息</i></div>
                        <form class="form-horizontal col-md-12" action="{{ url('admin/site') }}" autocomplete="off" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">网站名称</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="title" value="{{ app('request')->old('title', $title) }}">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">网站域名</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="domain" value="{{ app('request')->old('domain', $domain) }}">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">备案信息</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="beian" value="{{ app('request')->old('beian', $beian) }}">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">站长邮箱</label>
                                <div class="col-md-4">
                                    <input type="email" class="form-control" name="email" value="{{ app('request')->old('email', $email) }}">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">统计代码</label>
                                <div class="col-md-4">
                                    <textarea class="form-control" name="statistics" rows="10">{{ app('request')->old('statistics', $statistics) }}</textarea>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">版权信息</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="copyright" value="{{ app('request')->old('copyright', $copyright) }}">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">公司名称</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="company" value="{{ app('request')->old('company', $company) }}">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">开启调试模式</label>
                                <div class="col-md-4">
                                    <div class="btn-group btn-group-sm" data-toggle="buttons">
                                        @if($debug)
                                            <label class="btn btn-primary active"><input type="radio" name="debug" value="1" checked>开启</label>
                                            <label class="btn btn-primary"><input type="radio" name="debug" value="0">关闭</label>
                                        @else
                                            <label class="btn btn-primary"><input type="radio" name="debug" value="1">开启</label>
                                            <label class="btn btn-primary active"><input type="radio" name="debug" value="0" checked>关闭</label>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">URL方案</label>
                                <div class="col-md-4">
                                    <div class="btn-group btn-group-sm" data-toggle="buttons">
                                        @if($scheme == 1)
                                            <label class="btn btn-primary active"><input type="radio" name="scheme" value="1" checked>仅HTTPS</label>
                                            <label class="btn btn-primary"><input type="radio" name="scheme" value="2">仅HTTP</label>
                                            <label class="btn btn-primary"><input type="radio" name="scheme" value="0">自动</label>
                                        @endif
                                        @if($scheme == 2)
                                            <label class="btn btn-primary"><input type="radio" name="scheme" value="1">仅HTTPS</label>
                                            <label class="btn btn-primary active"><input type="radio" name="scheme" value="2" checked>仅HTTP</label>
                                            <label class="btn btn-primary"><input type="radio" name="scheme" value="0">自动</label>
                                        @endif
                                        @if($scheme == 0)
                                            <label class="btn btn-primary"><input type="radio" name="scheme" value="1">仅HTTPS</label>
                                            <label class="btn btn-primary"><input type="radio" name="scheme" value="2">仅HTTP</label>
                                            <label class="btn btn-primary active"><input type="radio" name="scheme" value="0" checked>自动</label>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">首页设置</label>
                                <div class="col-md-4">
                                    <select class="form-control" name="home">
                                        @if($home == 'default')
                                            <option value="default" selected>默认首页</option>
                                        @else
                                            <option value="default">默认首页</option>
                                        @endif
                                        @foreach($pages as $key=>$value)
                                            @if($home == 'page_' . $value['id'])
                                                <option value="page_{{ $value['id'] }}" selected>{{ $value['title'] }}</option>
                                            @else
                                                <option value="page_{{ $value['id'] }}">{{ $value['title'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label"></label>
                                <div class="col-md-4">
                                    <button class="btn btn-primary btn-sm" type="submit" style="width: 100%;">提交</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection