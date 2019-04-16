<div id="wmpack-admin">
    <?php include_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'admin/enable-pwa-btn.php'); ?>
    <div class="spacer-20"></div>
    <!-- set title -->
    <h1>Publisher's Toolbox PWA</h1>
    <div class="spacer-20"></div>
    <div class="whats-new">
        <div class="left-side">
            <!-- add nav menu -->
            <?php include_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'admin/sections/admin-menu.php'); ?>
            <div class="spacer-0"></div>
            <div class="details features">
                <h2 class="title">Get started with the Publisher's Toolbox PWA</h2>
                <div class="spacer-15"></div>
                <div class="grey-line"></div>
                <div class="spacer-15"></div>
                <div class="feature left">
                    <div class="text">
                        <?php
                            $step1 = plugins_url() . '/' . $Pt_Pwa_Config->PWA_DOMAIN . "/admin/images/step1.png";
                        ?>
                        <img src="<?php echo $step1; ?>" title="Step 1: Choose between two available app themes" />
                        <span class="title">Step 1: Choose between two available app themes</span>
                        <p>
                            There are two available themes listed to choose from: Newspaper and Magazine. This will determine the layout and presentation of the content. Any one theme will be activated, simply by clicking on the preferred option. To switch between the themes, simply click to activate the alternative theme.
                        </p>
                    </div>
                </div>
                <div class="spacer-0"></div>
                <div class="feature left">
                    <div class="text">
                        <?php
                            $step2 = plugins_url() . '/' . $Pt_Pwa_Config->PWA_DOMAIN . "/admin/images/step2.png";
                        ?>
                        <img src="<?php echo $step2; ?>" title="Step 2: Tailor the look & feel to suit your website" />
                        <span class="title">Step 2: Tailor the look & feel to suit your website</span>
                        <p>
                            Tailor the look & feel by choosing from the available color schemes, various images, adding your logo and app icon.
                        </p>
                    </div>
                </div>
                <div class="spacer-0"></div>
                <div class="feature left">
                    <div class="text">
                        <?php
                            $step3 = plugins_url() . '/' . $Pt_Pwa_Config->PWA_DOMAIN . "/admin/images/step3.png";
                        ?>
                        <img src="<?php echo $step3; ?>" title="Step 3: Decide on the content to be included in your layout" />
                        <span class="title">Step 3: Decide on the content to be included in your layout</span>
                        <p>
                            Initially all content will be activated to show on your mobile web application. Decide between various pages & categories to be activated/deactivated, by simply clicking on the row & selecting to activate/deactivate it. Categories & pages can also be rearranged by dragging the corresponding row to the desired position.
                        </p>
                    </div>
                </div>
                <div class="spacer-0"></div>
                <?php
                    $step4 = plugins_url() . '/' . $Pt_Pwa_Config->PWA_DOMAIN . "/admin/images/step4.png";
                ?>
                <div class="feature left">
                    <img src="<?php echo $step4 ?>" title="Step 4:  Change your settings, implement Google Analytics & social sharing" />
                    <div class="text">
                        <span class="title">Step 4: Change your settings, implement Google Analytics & social sharing</span>
                        <p>
                            Edit the app settings & add Google Tag Manager ID & Google Analytics Tracking Code to get insights from your visitors’ behaviour on your mobile web application. You also have the option to enable social sharing by activating/deactivating it.
                        </p>
                    </div>
                </div>
                <div class="spacer-0"></div>
            </div>
        </div>
        <div class="right-side"></div>
    </div>
</div>
