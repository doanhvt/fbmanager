<div class="side-options">
    <ul>
        <li>
            <a href="#" id="collapse-nav" class="act act-primary tip" title="Ẩn">
                <i class="icon16 i-arrow-left-7"></i>
            </a>
        </li>
    </ul>
</div>
<div class="sidebar-wrapper">
    <nav id="mainnav">
        <ul class="nav nav-list">
            <?php
            renderCategory($menu_data);

            function renderCategory($categoryList) {
                ?>
                <?php foreach ($categoryList as $item) { ?>
                    <li>
                        <a class="<?php echo isset($item["class"]) ? $item["class"] : ''; ?>" href="<?php echo (!isset($item["child"]) || !$item["child"] || !count($item["child"])) ? $item["url"] : "#"; ?>" title="<?php echo $item["text"] ?>">
                            <span class="icon">
                                <i class="icon20 <?php echo $item["icon"] ?>"></i>
                            </span>
                            <span class="txt"><?php echo $item["text"] ?></span>
                            <?php if (isset($item["child"]) && $item["child"] && count($item["child"]) > 0) { ?>
                                <ul class="sub">
                                    <?php renderCategory($item["child"]); ?>
                                </ul>
                            <?php } ?>
                        </a>
                    <?php } ?>
                <?php } ?>
                <!--            <li>
                                <a href="#">
                                    <span class="icon">
                                        <i class="icon20 i-menu-6"></i>
                                    </span>
                                    <span class="txt">Forms</span>
                                </a>
                                <ul class="sub">
                                    <li>
                                        <a href="form-elements.html">
                                            <span class="icon">
                                                <i class="icon20 i-stack-list"></i>
                                            </span>
                                            <span class="txt">Form elements</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="form-validation.html">
                                            <span class="icon">
                                                <i class="icon20 i-stack-checkmark"></i>
                                            </span>
                                            <span class="txt">Form validation</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="form-wizard.html">
                                            <span class="icon">
                                                <i class="icon20 i-stack-star"></i>
                                            </span>
                                            <span class="txt">Form wizard</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>-->
        </ul>
    </nav>
</div>