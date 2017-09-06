<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/"><span class="fa fa-coffee" aria-hidden="true"></span> Coffee</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                @foreach(config('navigation') as $navigation)
                    @if(isset($navigation['subnavigations']))
                        @if($navigation['type'] == 'group')
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:">
                                    <span class="glyphicon glyphicon-{{ $navigation['icon'] }}"></span>
                                    {{ $navigation['name'] }}
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach($navigation['subnavigations'] as $key => $subnavigations)
                                        <li class="disabled nav-title"><a href="#">{{ $key }}</a></li>
                                        @foreach($subnavigations as $subnavigation)
                                            <li class="nav-li">
                                                <a href="{{ route($subnavigation['url']) }}">
                                                    <span class="glyphicon glyphicon-{{ $subnavigation['icon'] }}"></span>
                                                    {{ $subnavigation['name'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                        <li role="separator" class="divider" style="clear: both;"></li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:">
                                    <span class="glyphicon glyphicon-{{ $navigation['icon'] }}"></span>
                                    {{ $navigation['name'] }}
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach($navigation['subnavigations'] as $subnavigation)
                                        <li>
                                            <a href="{{ route($subnavigation['url']) }}">
                                                <span class="glyphicon glyphicon-{{ $subnavigation['icon'] }}"></span>
                                                {{ $subnavigation['name'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @else
                        <li>
                            <a href="{{ route($navigation['url']) }}">
                                <span class="glyphicon glyphicon-{{ $navigation['icon'] }}"></span>
                                {{ $navigation['name'] }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#">换色</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">系统</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    @if(!empty(request()->user()))
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="glyphicon glyphicon-user" aria-hidden="true">{{request()->user()->name}}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#">修改密码</a></li>
                            <li><a href="/auth/logout">注销</a></li>
                        </ul>
                    @else
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/auth/login">登录</a></li>
                        </ul>
                    @endif
                </li>

            </ul>
        </div>
    </div>
</nav>