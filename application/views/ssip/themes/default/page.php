<!-- page container area start -->
    <div class="page-container">
        <!-- sidebar menu area start -->
        <?= $this->load->view($this->config->item('ssip') . 'navigation'); ?>
	    <!-- sidebar menu area end -->
        <!-- main content area start -->
        <div class="main-content">
            <!-- header area start -->
            <div class="header-area">
                <div class="row align-items-center">
                    <!-- nav and search button -->
                    <div class="col-md-6 col-sm-8 clearfix">
                        <div class="nav-btn pull-left">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <div class="search-box pull-left">
                        </div>
                    </div>
                    <!-- profile info & task notification -->
                    <div class="col-md-6 col-sm-4 clearfix">
                      <?= $this->load->view($this->config->item('ssip') . 'menubar'); ?>  
                    </div>
                </div>
            </div>
            <!-- header area end -->
            <!-- page title area start -->
            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                           <?= $this->load->view($this->config->item('ssip') . 'breadcrumb'); ?> 
                        </div>
                    </div>
					<!-- profile area start -->
                    <div class="col-sm-6 clearfix">
                        <?= $this->load->view($this->config->item('ssip') . 'profile'); ?>
                    </div>
					<!-- profile area start -->
                </div>
            </div>
            <!-- page title area end -->
            <div class="main-content-inner">
			<?= $this->load->view($this->config->item('ssip') . 'content'); ?>
            </div>
        </div>
        <!-- main content area end -->
        <!-- footer area start-->
        <footer>
            <div class="footer-area">
                � Copyright 2019. All right reserved. Template by <a href="https://colorlib.com/wp/">Colorlib</a>.
            </div>
        </footer>
        <!-- footer area end-->
    </div>
    <!-- page container area end -->