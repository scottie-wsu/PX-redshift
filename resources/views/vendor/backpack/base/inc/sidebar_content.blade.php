<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->


<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('methods') }}'><i class='nav-icon la la-calculator'></i> Methods</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('users') }}'><i class='nav-icon la la-users'></i> Users</a></li>
<!--<li class="nav-item"><a class="nav-link" href='{{ backpack_url('analytics') }}'><i class="nav-icon la la-chart-bar"></i> Analytics</a></li>-->
<li class="nav-item nav-dropdown">
<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cogs"></i> Analytics</a>
<ul class="nav-dropdown-items">
    <li class="nav-item"><a class="nav-link" href='{{ backpack_url('analytics') }}'><i class="nav-icon la la-chart-bar"></i> Quick plots</a></li>
    <li class="nav-item"><a class="nav-link" href='{{ backpack_url('plotting') }}'><i class="nav-icon la la-chart-bar"></i> Choose your data</a></li>
</ul>
</li>







