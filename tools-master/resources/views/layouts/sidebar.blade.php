<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="{!! route('home') !!}"><i class="icon-home"></i> Main Dashboard</a>
            </li>
            <li>
                <a class="nav-link" href="{!! route('true-dashboard') !!}">
                    <i class="fa fa-bar-chart-o"></i><span class="hidden-tablet"> True Dashboard</span>
                </a>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-bar-chart-o"></i>
                    <span class="hidden-tablet"> More Dashboards</span>
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link" href="{!! route('get-pacing') !!}">
                            <i class="fa fa-bar-chart-o"></i><span class="hidden-tablet"> Campaign Pacing </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{!! route('get-partner-pacing') !!}">
                            <i class="fa fa-bar-chart-o"></i><span class="hidden-tablet"> API Partner Pacing</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{!! route('pacing-traffic') !!}">
                            <i class="fa fa-bar-chart-o"></i><span class="hidden-tablet"> Traffic Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{!! route('pacing-traffic-breakage') !!}">
                            <i class="fa fa-bar-chart-o"></i><span class="hidden-tablet"> Traffic Breakage</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{!! route('pacing-traffic-breakdown') !!}">
                            <i class="fa fa-bar-chart-o"></i><span class="hidden-tablet"> Traffic Distribution</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-tachometer"></i> Commerce Dashboards</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link" href="{!! route('get-top-merchants') !!}"><i class="fa fa-tachometer"></i> Top Merchants</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{!! route('get-merchants-performance') !!}"><i class="fa fa-tachometer"></i> Merchant Performance</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{!! route('merchant-rpc-archive') !!}"><i class="fa fa-tachometer"></i> Merchant RPC Archives</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{!! route('merchant-breakdown') !!}"><i class="fa fa-tachometer"></i> Domain Breakdown</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-tachometer"></i> Traffic</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link" href="{!! route('campaigns.index') !!}"><i class="fa fa-tachometer"></i> Campaigns</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{!! route('advertisers.index') !!}"><i class="fa fa-tachometer"></i> Advertisers</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-wrench"></i> Tools</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link" href="{!! route('adrenalads-api-tester') !!}"><i class="fa fa-wrench"></i> Test API</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{!! route('adrenalads-api-phone-tester') !!}"><i class="fa fa-wrench"></i> Test Phone API</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{!! route('geoip') !!}"><i class="fa fa-wrench"></i> GeoIP Lookup</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{!! route('alerts-index') !!}">
                    <i class="icon-exclamation"></i> Alerts
                    @if(count($important_alerts) > 0)
                        <i class="alerts-triangle-red fa fa-exclamation-triangle"></i><span class="badge badge-danger">{{ count($important_alerts) }}</span>
                    @endif

                </a>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-power"></i> IPMI</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link" target="_blank" href="https://colo.adrenalads.com:9132"><i class="icon-power"></i> Artoo (9132)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" target="_blank" href="https://colo.adrenalads.com:9152"><i class="icon-power"></i> BB8 (9152)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" target="_blank" href="https://colo.adrenalads.com:9131"><i class="icon-power"></i> Finn (9131)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" target="_blank" href="https://colo.adrenalads.com:9140"><i class="icon-power"></i> Kenobi (9140)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" target="_blank" href="https://colo.adrenalads.com:9151"><i class="icon-power"></i> Mace (9151)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" target="_blank" href="https://colo.adrenalads.com:9130"><i class="icon-power"></i> Rey (9130)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" target="_blank" href="https://colo.adrenalads.com:9141"><i class="icon-power"></i> Threepio (9141)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" target="_blank" href="https://colo.adrenalads.com:9150"><i class="icon-power"></i> Vader (9150)</a>
                    </li>
                </ul>
           </li>
            <li class="nav-item">
                <a class="nav-link" href="{!! route('users.index') !!}"><i class="icon-people"></i> Users</a>
            </li>

        </ul>
    </nav>
</div>
