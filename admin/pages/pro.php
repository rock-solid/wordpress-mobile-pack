<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){

            WMPJSInterface.localpath = "<?php echo plugins_url()."/".WMP_DOMAIN."/"; ?>";
            WMPJSInterface.init();
        });
    }
</script>
<div id="wmpack-admin">
    <div class="spacer-60"></div>
    <!-- set title -->
    <h1><?php echo WMP_PLUGIN_NAME.' '.WMP_VERSION;?></h1>
    <div class="spacer-20"></div>

    <div class="pro">
        <div class="left-side">

            <!-- add nav menu -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/admin-menu.php'); ?>
            <div class="spacer-0"></div>

            <?php
                $upgrade_content = WMobilePack_Admin::more_updates();

                if (is_array($upgrade_content) && !empty($upgrade_content)):
                    if (array_key_exists('premium', $upgrade_content) && array_key_exists('packages', $upgrade_content['premium'])):
            ?>

                    <div class="details">
                        <div class="spacer-10"></div>

						<?php if (isset($upgrade_content['premium']['packages']['title'])):?>
							<h1><?php echo $upgrade_content['premium']['packages']['title'];?></h1>
							<div class="spacer-30"></div>
						<?php endif;?>

						<?php if (isset($upgrade_content['premium']['packages']['description'])):?>
							<p style="text-align: center">
								<?php echo $upgrade_content['premium']['packages']['description'];?>
							</p>
							<div class="spacer-30"></div>
						<?php endif;?>

						<?php if (isset($upgrade_content['premium']['packages']['image']) && $upgrade_content['premium']['packages']['image'] != ''):?>
							<div class="showcase">
								<img src="<?php echo esc_attr($upgrade_content['premium']['packages']['image']);?>" width="671" />
							</div>
							<div class="spacer-30"></div>
						<?php endif;?>

						<?php if (isset($upgrade_content['premium']['packages']['bundles']) && is_array($upgrade_content['premium']['packages']['bundles'])):?>

							<?php foreach ($upgrade_content['premium']['packages']['bundles'] as $bundle):?>

								<div class="package">

									<?php if (isset($bundle['title'])):?>
										<h2><strong><?php echo $bundle['title'];?></strong></h2>
										<div class="spacer-20"></div>
									<?php endif;?>

									<?php if (isset($bundle['features']) && is_array($bundle['features'])):?>
										<div class="features-list">
											<?php foreach ($bundle['features'] as $feature):?>
												<div>
													<span><?php echo $feature;?></span>
												</div>
											<?php endforeach;?>
										</div>
									<?php endif;?>
								</div>
							<?php endforeach;?>

							<div class="spacer-20"></div>
						<?php endif;?>

						<?php if (isset($upgrade_content['premium']['packages']['button']['text']) && isset($upgrade_content['premium']['packages']['button']['link'])):?>
							<div class="upgrade-btns">
								<a href="<?php echo esc_attr($upgrade_content['premium']['packages']['button']['link']);?>" class="btn orange smaller" target="_blank">
									<?php echo $upgrade_content['premium']['packages']['button']['text'];?>
								</a>
								<?php if (isset($upgrade_content['premium']['packages']['button']['subtext']['discount']) &&
										 isset($upgrade_content['premium']['packages']['button']['subtext']['text'])) :?>
									<div class="spacer-2"></div>
									<p class="upgrade-subtext">
										<span class="save"><?php echo $upgrade_content['premium']['packages']['button']['subtext']['discount'];?></span>
										<?php echo $upgrade_content['premium']['packages']['button']['subtext']['text'];?>
									</p>
								<?php endif;?>
							</div>
							<div class="spacer-40"></div>
						<?php endif;?>

						<?php if (isset($upgrade_content['premium']['packages']['second_title'])):?>
							<h2><?php echo $upgrade_content['premium']['packages']['second_title'] ?></h2>
							<div class="spacer-20"></div>
						<?php endif;?>

						<?php if (isset($upgrade_content['premium']['packages']['features']) && is_array($upgrade_content['premium']['packages']['features'])):?>

							<?php foreach ($upgrade_content['premium']['packages']['features'] as $main_feature):?>

								<div class="package">

									<?php if (isset($main_feature['icon'])):?>
										<div><span class="icon-<?php echo esc_attr($main_feature['icon']);?>"></span></div>
									<?php endif;?>

									<?php if (isset($main_feature['title'])):?>
										<h3><?php echo $main_feature['title'];?></h3>
										<div class="spacer-0"></div>
									<?php endif;?>

									<?php if (isset($main_feature['description'])):?>
										<p><?php echo $main_feature['description'];?></p>
										<div class="spacer-0"></div>
									<?php endif;?>
								</div>

							<?php endforeach;?>
						<?php endif;?>
                        <div class="spacer-20"></div>

						<?php if (isset($upgrade_content['premium']['packages']['button']['text']) && isset($upgrade_content['premium']['packages']['button']['link'])):?>
							<div class="upgrade-btns">
								<a href="<?php echo esc_attr($upgrade_content['premium']['packages']['button']['link']);?>" class="btn orange smaller" target="_blank">
									<?php echo $upgrade_content['premium']['packages']['button']['text'];?>
								</a>
								<?php if (isset($upgrade_content['premium']['packages']['button']['subtext']['discount']) &&
									isset($upgrade_content['premium']['packages']['button']['subtext']['text'])) :?>
									<div class="spacer-2"></div>
									<p class="upgrade-subtext">
										<span class="save"><?php echo $upgrade_content['premium']['packages']['button']['subtext']['discount'];?></span>
										<?php echo $upgrade_content['premium']['packages']['button']['subtext']['text'];?>
									</p>
								<?php endif;?>
							</div>
							<div class="spacer-40"></div>
						<?php endif;?>
                    </div>
            <?php
					endif;
                elseif ($upgrade_content == 'warning'):
            ?>
            	<div class="details">
                    <div class="spacer-10"></div>
                    <div class="message-container warning">
                        <div class="wrapper">
                            <div class="title">
                                <h2 class="underlined">Can't check for updates!</h2>
                            </div>
                            <span>We are unable to display the content on this page due to the fact that both <a href="https://php.net/manual/en/book.curl.php" target="_blank">cURL</a> and <a href="http://www.php.net/manual/en/function.fopen.php" target="_blank">fopen</a> are disabled.</span>
                        </div>
                    </div>
                    <div class="spacer-10"></div>
                </div>
            <?php endif;?>
        </div>

        <div class="right-side">
            <!-- waitlist form -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/waitlist.php'); ?>

            <!-- add feedback form -->
            <?php include_once(WMP_PLUGIN_PATH.'admin/sections/feedback.php'); ?>
        </div>
    </div>
</div>
