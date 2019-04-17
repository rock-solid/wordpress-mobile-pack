<?php
    /**
     *
     * Enable PWA Button
     * Defaults to 'off' until theme file is saved
     *
     */
?>
<div class="custom-checkboxes">
    <input type="checkbox" id="enable-pt-pwa" name="enable-pt-pwa" value="enable-pt-pwa" <?php echo $Pt_Pwa_Config->PWA_ENABLED ? 'checked' : ''; ?> disabled>
    <label for="enable-pt-pwa">
      <span>
      	<?php if ($Pt_Pwa_Config->PWA_ENABLED) { ?>
            <img src="<?php echo plugins_url() . '/' . $Pt_Pwa_Config->PWA_DOMAIN . '/admin/images/check-icn.svg'; ?>" alt="check" />
        <?php } else { ?>
            <div class="x">X</div>
        <?php } ?>
      </span>
    </label>
    <?php if ($Pt_Pwa_Config->PWA_ENABLED) { ?>
        <span class="label">PWA Enabled</span>
        <form id="disable-pwa" method="post">
            <input type="submit" class="disable-pt-pwa" name="disable-pt-pwa" value="Disable PWA">
        </form>
        <img src="<?php echo plugins_url() . '/' . $Pt_Pwa_Config->PWA_DOMAIN . '/admin/images/loading.gif'; ?>" width="36" height="36" class="loader" alt="loader" />
        <?php if (!empty($_POST['disable-pt-pwa'])) {
            $Pt_Pwa_Config->disable_pwa();
        } ?>
    <?php } else { ?>
        <span class="label">PWA Disabled</span>
    <?php } ?>
</div>
