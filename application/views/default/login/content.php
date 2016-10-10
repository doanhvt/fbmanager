<div class="container-fluid">
    <div id="login">
        <div class="login-wrapper" data-active="log"> 
            <a class="brand" href="<?php echo site_url(); ?>">
                <img src="<?php echo $this->path_static_file; ?>images/logo_black.png" alt="Topmito Manager">
            </a>
            <div id="log">
                <div class="page-header">
                    <h3 class="center">Login - MOL Topmito Manager</h3>
                </div>
                <form id="login-form" class="form-horizontal e_ajax_submit" action="<?php echo $login_url; ?>">
                    <div class="row-fluid">
                        <div class="control-group">
                            <div class="controls-row">
                                <div class="icon"><i class="icon20 i-user"></i></div>
                                <input class="span12 valid_item" name="admin_email" id="admin_email" placeholder="<?php echo $form["field_form"]["userName"]; ?>" <?php echo $form["rule"]["userName"]; ?> />
                                <label class="error"></label>
                            </div>
                        </div>
                        <!-- End .control-group -->
                        <div class="control-group">
                            <div class="controls-row">
                                <div class="icon"><i class="icon20 i-key"></i></div>
                                <input class="span12 valid_item" type=password name="admin_password" id="admin_password" placeholder="<?php echo $form["field_form"]["userPassword"]; ?>" <?php echo $form["rule"]["userPassword"]; ?> />
                                <label class="error"></label>
                            </div>
                        </div>
                        <!-- End .control-group -->
                        <div class="form-actions full">
                            <label class="checkbox pull-left hidden">
                                <input type="checkbox" value="1" name="remember">
                                <span class="pad-left5">Remember ?</span>
                            </label>
                            <button id="loginBtn" type="submit" class="btn btn-primary pull-right span5">Login</button>
                        </div>
                    </div>
                    <!-- End .row-fluid -->
                </form>
            </div>
            <div id="forgot">
                <div class="page-header">
                    <h3 class="center">Forgot password</h3>
                </div>
                <form class="form-horizontal" action="<?php echo $recover_url; ?>">
                    <div class="row-fluid">
                        <div class="control-group">
                            <div class="controls-row">
                                <div class="icon"><i class="icon20 i-envelop-2"></i></div>
                                <input class="span12" type="text" name="email" id="email-field" placeholder="Email">
                            </div>
                        </div>
                        <!-- End .control-group -->
                        <div class="form-actions full">
                            <button type="submit" class="btn btn-large btn-block btn-success">Get password</button>
                        </div>
                    </div>
                    <!-- End .row-fluid -->
                </form>
            </div>
        </div>
        <div id="bar" data-active="log">
            <div class="btn-group btn-group-vertical"> 
                <a id="log" href="#" class="btn tipR" title="Login"><i class="icon16 i-key"></i></a> 
                <a id="forgot" href="#" class="btn tipR" title="Forgot password"><i class="icon16 i-question"></i></a> 
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>