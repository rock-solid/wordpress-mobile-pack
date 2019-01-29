<?php

	$themeManager = new PtPwaThemeManager(new PtPwaTheme());
	$themeFile = $themeManager->getTheme();

	if($_SERVER['REQUEST_METHOD'] == 'POST') {

		if(isset($_POST['Newspaper'])) {
			$themeFile->setLayout(1);
		} elseif(isset($_POST['Magazine'])) {
			$themeFile->setLayout(2);
		}

		$themeManager->write();
	}

	$arr_themes = array(
		array(
			'id' => 3,
			'pwa_layout_id' => 1,
			'title'=> 'Magazine',
			'icon' => plugins_url().'/'.$Pt_Pwa_Config->PWA_DOMAIN.'/admin/images/themes/theme-4.jpg',
			'selected' => intval($themeFile->getLayout() == 1)
		),
		array(
			'id' => 4,
			'pwa_layout_id' => 2,
			'title'=> 'Newspaper',
			'icon' => plugins_url().'/'.$Pt_Pwa_Config->PWA_DOMAIN.'/admin/images/themes/theme-3.jpg',
			'selected' => intval($themeFile->getLayout() == 2)
		),
	);
?>

<div id="wmpack-admin">
	<div class="spacer-60"></div>
    <!-- set title -->
    <h1>Publisher's Toolbox PWA <?= $Pt_Pwa_Config->PWA_VERSION ?></h1>
	<div class="spacer-20"></div>
	<div class="themes">
        <div class="left-side">

            <!-- add nav menu -->
            <?php include_once($Pt_Pwa_Config->PWA_PLUGIN_PATH.'admin/sections/admin-menu.php'); ?>
            <div class="spacer-0"></div>
			<form method="post" enctype="multipart/form-data">
				<?php if (count($arr_themes) > 0):?>

					<div class="details theming">

						<h2 class="title">Available Mobile App Themes</h2>
						<div class="spacer-30"></div>

						<div class="themes" >
							<?php
								foreach ($arr_themes as $theme){
									require($Pt_Pwa_Config->PWA_PLUGIN_PATH.'admin/sections/theme-box.php');
								}
							?>
						</div>
						<div class="spacer-0"></div>
					</div>
					<div class="spacer-10"></div>

				<?php endif;?>
			</form>
			<div class="spacer-10"></div>
        </div>

        <div class="right-side">
        </div>
	</div>
</div>
