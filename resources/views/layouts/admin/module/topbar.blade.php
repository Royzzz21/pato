    <div class="overlay"></div>
    <nav class="navbar">
        <div class="container-fluid plr0">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="/admin">Patoco Admin</a>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">
						@if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
						@endif
						<?php // 로그인이면
							if (Auth::check()) {
						?>
							<div>
								<i class="material-icons">person</i><span>email</span>
							</div>
							<div>
								<button type="button" class="btn btn-default waves-effect">mypage</button>
								
								<button type="button" class="btn btn-default waves-effect" onclick="javascript:location.href='{{ route('logout') }}';event.preventDefault();
                                                     document.getElementById('logout-form').submit();">logout</button>
								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
							</div>
						<?php
							}else{
						?>
							<div>
								<i class="material-icons">person</i>
								<span style="line-height:1.1px;">로그인하세요</span>
							</div>

						<?php
							}
						?>



					</li>
					<li class="active">
						<a href="/admin/member/">
							{{--<i class="material-icons">account_balance_wallet</i>--}}


                            <span>Membership Management</span>
						</a>
					</li>

					<li>
						<a href="/admin/bbs/list">
                            <span>board Management</span>
						</a>
					</li>

					<li>
                        <a href="/">

                            <span>Service center</span>
                        </a>
                    </li>

                    <li>
                        <a href="/dashboard"><span>dashboard</span></a>
                    </li>


                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">

                            <span>main</span>
                        </a>
                        <ul class="ml-menu">
							<li>
								<a href="/">
									<span>공지사항</span>
								</a>
							</li>
                            <li>
                                <a href="javascript:void(0);" class="menu-toggle">
                                    <span>소개</span>
                                </a>
                                <ul class="ml-menu">
                                    <li>
                                        <a href="../../pages/widgets/cards/basic.html">리</a>
                                    </li>
                                    <li>
                                        <a href="../../pages/widgets/cards/colored.html">아</a>
                                    </li>
                                    <li>
                                        <a href="../../pages/widgets/cards/no-header.html">라이</a>
                                    </li>
                                </ul>
                            </li>
							<li>
								<a href="/">
									<span>회사소개</span>
								</a>
							</li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; 2018 <a href="javascript:void(0);">&nbsp;&nbsp;patoco</a>
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
        <!-- Right Sidebar -->
        <aside id="rightsidebar" class="right-sidebar">
            <ul class="nav nav-tabs tab-nav-right" role="tablist">
                <li role="presentation" class="active"><a href="#skins" data-toggle="tab">SKINS</a></li>
                <li role="presentation"><a href="#settings" data-toggle="tab">SETTINGS</a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
                    <ul class="demo-choose-skin">
                        <li data-theme="red" class="active">
                            <div class="red"></div>
                            <span>Red</span>
                        </li>
                        <li data-theme="pink">
                            <div class="pink"></div>
                            <span>Pink</span>
                        </li>
                        <li data-theme="purple">
                            <div class="purple"></div>
                            <span>Purple</span>
                        </li>
                        <li data-theme="deep-purple">
                            <div class="deep-purple"></div>
                            <span>Deep Purple</span>
                        </li>
                        <li data-theme="indigo">
                            <div class="indigo"></div>
                            <span>Indigo</span>
                        </li>
                        <li data-theme="blue">
                            <div class="blue"></div>
                            <span>Blue</span>
                        </li>
                        <li data-theme="light-blue">
                            <div class="light-blue"></div>
                            <span>Light Blue</span>
                        </li>
                        <li data-theme="cyan">
                            <div class="cyan"></div>
                            <span>Cyan</span>
                        </li>
                        <li data-theme="teal">
                            <div class="teal"></div>
                            <span>Teal</span>
                        </li>
                        <li data-theme="green">
                            <div class="green"></div>
                            <span>Green</span>
                        </li>
                        <li data-theme="light-green">
                            <div class="light-green"></div>
                            <span>Light Green</span>
                        </li>
                        <li data-theme="lime">
                            <div class="lime"></div>
                            <span>Lime</span>
                        </li>
                        <li data-theme="yellow">
                            <div class="yellow"></div>
                            <span>Yellow</span>
                        </li>
                        <li data-theme="amber">
                            <div class="amber"></div>
                            <span>Amber</span>
                        </li>
                        <li data-theme="orange">
                            <div class="orange"></div>
                            <span>Orange</span>
                        </li>
                        <li data-theme="deep-orange">
                            <div class="deep-orange"></div>
                            <span>Deep Orange</span>
                        </li>
                        <li data-theme="brown">
                            <div class="brown"></div>
                            <span>Brown</span>
                        </li>
                        <li data-theme="grey">
                            <div class="grey"></div>
                            <span>Grey</span>
                        </li>
                        <li data-theme="blue-grey">
                            <div class="blue-grey"></div>
                            <span>Blue Grey</span>
                        </li>
                        <li data-theme="black">
                            <div class="black"></div>
                            <span>Black</span>
                        </li>
                    </ul>
                </div>
				
				
                <div role="tabpanel" class="tab-pane fade" id="settings">
                    <div class="demo-settings">
                        <p>GENERAL SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Report Panel Usage</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Email Redirect</span>
                                <div class="switch">
                                    <label><input type="checkbox"><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                        <p>SYSTEM SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Notifications</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Auto Updates</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                        <p>ACCOUNT SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Offline</span>
                                <div class="switch">
                                    <label><input type="checkbox"><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Location Permission</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
				
				
				
            </div>
        </aside>
        <!-- #END# Right Sidebar -->
    </section>