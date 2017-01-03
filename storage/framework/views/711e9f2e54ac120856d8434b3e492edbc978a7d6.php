<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <?php /*<ul class="sidebar-menu">*/ ?>
            <?php /*<li class="header">MAIN NAVIGATION</li>*/ ?>
            <?php /*<li class="<?php echo e(Request::is('home') ? 'active':''); ?>" >*/ ?>
                <?php /*<a href="home"> <i class="fa fa-home"></i> <span>Home</span> </a>*/ ?>
            <?php /*</li>*/ ?>

            <?php /*<!--Menu Inventory-->*/ ?>
            <?php /*<li class="treeview <?php echo e(Request::is('inventory/*') ? 'active':''); ?>" >*/ ?>
                <?php /*<a href="#">*/ ?>
                    <?php /*<i class="fa fa-th"></i>*/ ?>
                    <?php /*<span>Inventory</span>*/ ?>
                    <?php /*<i class="fa fa-angle-left pull-right"></i>*/ ?>
                <?php /*</a>*/ ?>
                <?php /*<ul class="treeview-menu">*/ ?>
                    <?php /*<li class="<?php echo e(Request::is('inventory/barang*') ? 'active':''); ?>" ><a href="inventory/barang"><i class="fa fa-circle-o"></i> Barang</a></li>*/ ?>
                    <?php /*<li class="<?php echo e(Request::is('inventory/kategori*') ? 'active':''); ?>" ><a href="inventory/kategori"><i class="fa fa-circle-o"></i> Kategori</a></li>*/ ?>
                    <?php /*<li class="<?php echo e(Request::is('inventory/satuan*') ? 'active':''); ?>" ><a href="inventory/satuan"><i class="fa fa-circle-o"></i> Satuan</a></li>*/ ?>
                    <?php /*<li class="<?php echo e(Request::is('inventory/adjustment*') ? 'active':''); ?>" ><a href="inventory/adjustment"><i class="fa fa-circle-o"></i> Inventory Adjustment</a></li>*/ ?>
                <?php /*</ul>*/ ?>
            <?php /*</li>*/ ?>
            <?php /*<!--Menu Purchase-->*/ ?>
            <?php /*<li class="treeview <?php echo e(Request::is('purchase/*') ? 'active':''); ?>" >*/ ?>
                <?php /*<a href="#">*/ ?>
                    <?php /*<i class="fa fa-calculator"></i>*/ ?>
                    <?php /*<span>Purchase</span>*/ ?>
                    <?php /*<i class="fa fa-angle-left pull-right"></i>*/ ?>
                <?php /*</a>*/ ?>
                <?php /*<ul class="treeview-menu">*/ ?>
                    <?php /*<li class="<?php echo e(Request::is('purchase/order/add') ? 'active':''); ?>" ><a href="purchase/order/add"><i class="fa fa-circle-o"></i> Add Purchase Orders</a></li>*/ ?>
                    <?php /*<li class="<?php echo e(Request::is('purchase/order*') ? 'active':''); ?>" ><a href="purchase/order"><i class="fa fa-circle-o"></i> Purchase Orders</a></li>*/ ?>
                    <?php /*<li class="<?php echo e(Request::is('purchase/supplier*') ? 'active':''); ?>" ><a href="purchase/supplier"><i class="fa fa-circle-o"></i> Supplier</a></li>*/ ?>
                    <?php /*<li class="<?php echo e(Request::is('purchase/report*') ? 'active':''); ?>" ><a href="purchase/report"><i class="fa fa-circle-o"></i> Reports</a></li>*/ ?>

                <?php /*</ul>*/ ?>
            <?php /*</li>*/ ?>
            <?php /*<!--Menu Sales-->*/ ?>
            <?php /*<li class="treeview <?php echo e(Request::is('sales/*') ? 'active':''); ?>" >*/ ?>
                <?php /*<a href="#">*/ ?>
                    <?php /*<i class="fa fa-shopping-cart"></i>*/ ?>
                    <?php /*<span>Sales</span>*/ ?>
                    <?php /*<i class="fa fa-angle-left pull-right"></i>*/ ?>
                <?php /*</a>*/ ?>
                <?php /*<ul class="treeview-menu">*/ ?>
                    <?php /*<li class="" >*/ ?>
                        <?php /*<li class="<?php echo e(Request::is('sales/order/add') ? 'active':''); ?>" ><a href="sales/order/add"><i class="fa fa-circle-o"></i> Add Sales Orders</a></li>*/ ?>
                        <?php /*<li class="<?php echo e(Request::is('sales/order*') ? 'active':''); ?>" ><a href="sales/order"><i class="fa fa-circle-o"></i> Sales Orders</a></li>*/ ?>
                        <?php /*<li class="<?php echo e(Request::is('sales/customer*') ? 'active':''); ?>" ><a href="sales/customer"><i class="fa fa-circle-o"></i> Customer</a></li>*/ ?>
                        <?php /*<li class="<?php echo e(Request::is('sales/salesman*') ? 'active':''); ?>" ><a href="sales/salesman"><i class="fa fa-circle-o"></i> Salesperson</a></li>*/ ?>
                    <?php /*</li>*/ ?>
                <?php /*</ul>*/ ?>
            <?php /*</li>*/ ?>
            <?php /*<!--Menu Invoicing-->*/ ?>
            <?php /*<li class="treeview <?php echo e(Request::is('invoice/*') ? 'active':''); ?>" >*/ ?>
                <?php /*<a href="#">*/ ?>
                    <?php /*<i class="fa fa-newspaper-o"></i>*/ ?>
                    <?php /*<span>Invoices</span>*/ ?>
                    <?php /*<i class="fa fa-angle-left pull-right"></i>*/ ?>
                <?php /*</a>*/ ?>
                <?php /*<ul class="treeview-menu">*/ ?>
                    <?php /*<li class="" >*/ ?>
                        <?php /*<li class="<?php echo e(Request::is('invoice/customer-invoice*') ? 'active':''); ?>" ><a href="invoice/customer-invoice"><i class="fa fa-circle-o"></i> Customer Invoices</a></li>*/ ?>
                        <?php /*<li class="<?php echo e(Request::is('invoice/customer/payment*') ? 'active':''); ?>" ><a href="invoice/customer/payment"><i class="fa fa-circle-o"></i> Customer Payments</a></li>*/ ?>
                        <?php /*<li class="<?php echo e(Request::is('invoice/supplier-bill*') ? 'active':''); ?>" ><a href="invoice/supplier-bill"><i class="fa fa-circle-o"></i> Supplier Bills</a></li>*/ ?>
                        <?php /* <li class="<?php echo e(Request::is('invoice/supplier/bill-payment*') ? 'active':''); ?>" ><a href="invoice/supplier/bill-payment"><i class="fa fa-circle-o"></i> Bill Payments</a></li>          */ ?>
                    <?php /*</li>*/ ?>
                <?php /*</ul>*/ ?>
            <?php /*</li>*/ ?>
            <?php /*<li class="treeview <?php echo e(Request::is('cashbook/*') ? 'active':''); ?>" >*/ ?>
                <?php /*<a href="#">*/ ?>
                    <?php /*<i class="fa fa-money"></i>*/ ?>
                    <?php /*<span>Cash Flow</span>*/ ?>
                    <?php /*<i class="fa fa-angle-left pull-right"></i>*/ ?>
                <?php /*</a>*/ ?>
                <?php /*<ul class="treeview-menu">*/ ?>
                    <?php /*<li class="" >*/ ?>
                        <?php /*<li class="<?php echo e(Request::is('cashbook/expense*') ? 'active':''); ?>" ><a href="cashbook/expense"><i class="fa fa-circle-o"></i> Cash Expense</a></li>*/ ?>

                        <?php /*<li class="<?php echo e(Request::is('cashbook/receipt*') ? 'active':''); ?>" ><a href="cashbook/receipt"><i class="fa fa-circle-o"></i> Cash Receipt</a></li>*/ ?>

                        <?php /*<li class="<?php echo e(Request::is('cashbook/pettycash*') ? 'active':''); ?>" ><a href="cashbook/pettycash"><i class="fa fa-circle-o"></i> Petty Cash</a></li>*/ ?>
                    <?php /*</li>*/ ?>
                <?php /*</ul>*/ ?>
            <?php /*</li>*/ ?>

            <?php /*<!-- <li class="">*/ ?>
                <?php /*<a href="#">*/ ?>
                    <?php /*<i class="fa fa-laptop"></i>*/ ?>
                    <?php /*<span>Blogs</span>*/ ?>
                    <?php /*<i class="fa fa-angle-left pull-right"></i>*/ ?>
                <?php /*</a>*/ ?>
            <?php /*</li> -->*/ ?>

        <?php /*</ul>*/ ?>

        <ul class="sidebar-menu">

            <?php foreach($sidemenu as $dt ): ?>
                <?php if(count($dt->childmenu) > 0): ?>
                    <li class="treeview <?php echo e(Request::is($dt->class_request) ? 'active':''); ?>" >
                        <a href="<?php echo e($dt->href); ?>">
                            <i class="<?php echo e($dt->icon); ?>"></i>
                            <span><?php echo e($dt->title); ?></span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php foreach($dt->childmenu as $chd): ?>
                                <li class="<?php echo e(Request::is($chd->class_request) ? 'active':''); ?>" ><a href="<?php echo e($chd->href); ?>"><i class="fa fa-circle-o"></i> <?php echo e($chd->title); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php else: ?>

                    <?php if(!$dt->parentMenu): ?>
                        <li class="<?php echo e(Request::is($dt->class_request) ? 'active':''); ?>" >
                            <a href="<?php echo e($dt->href); ?>">
                                <i class="<?php echo e($dt->icon); ?>"></i>
                                <span><?php echo e($dt->title); ?> </span>
                            </a>
                        </li>
        <?php endif; ?>
        <?php endif; ?>
        <?php endforeach; ?>

        </ul>

    </section>
    <!-- /.sidebar -->
</aside>
