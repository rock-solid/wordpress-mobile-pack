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

      	<?php if ($Pt_Pwa_Config->PWA_ENABLED) : ?>
            <img src="<?php echo plugins_url() . '/' . $Pt_Pwa_Config->PWA_DOMAIN . '/admin/images/check-icn.svg'; ?>" />
        <?php else : ?>
            <div class="x">X</div>
        <?php endif; ?>

      </span>
    </label>
    <?php

        if ($Pt_Pwa_Config->PWA_ENABLED) : ?>
            <span class="label">PWA Enabled</span>
            <form id="disable-pwa" method="post">
                <input type="submit" class="disable-pt-pwa" name="disable-pt-pwa" value="Disable PWA">
            </form>
            <img src="<?php echo plugins_url() . '/' . $Pt_Pwa_Config->PWA_DOMAIN . '/admin/images/loading.gif'; ?>" width="36" height="36" class="loader" />
            <?php if (!empty($_POST['disable-pt-pwa'])) :

                $Pt_Pwa_Config->disable_pwa();

            endif;
        else : ?>
            <span class="label">PWA Disabled</span>
        <?php endif; ?>
</div>
<style>
    input.disable-pt-pwa {
        display: inline-block;
        margin-left: 20px;
        height: 36px;
        background: #ffb900;
        border: 2px solid white;
        color: white !important;
        text-align: center;
        font-size: 18px;
        font-weight: bold !important;
        padding: 0 10px;
        transition: all 0.3s ease-in-out;
        margin-right: 10px;
        cursor: pointer;
        outline: none !important;
    }

    input.disable-pt-pwa:hover {
        background: white;
        color: #ffb900 !important;
    }

    img.loader {
        opacity: 0;
        transition: all 0.3S ease-in-out;
    }

    img.loader.visible {
        opacity: 1;
    }

    .custom-checkboxes {
        display: flex;
        margin-top: 20px;
    }

    .custom-checkboxes span.label {
        font-size: 30px !important;
        font-weight: bold !important;
        margin-left: 10px !important;
        height: 40px !important;
        line-height: 40px !important;
    }

    .custom-checkboxes input[type="checkbox"] {
        display: none;
    }

    .custom-checkboxes input[type="checkbox"] + label {
        color: #333;
        font-family: Arial, sans-serif;
        font-size: 14px;

    }

    .custom-checkboxes input[type="checkbox"] + label span {
        display: inline-block;
        width: 40px;
        height: 40px;
        margin: -1px 4px 0 0;
        vertical-align: middle;
        cursor: default;
        border-radius: 50%;
        border: 2px solid #FFFFFF;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.33);
        background-repeat: no-repeat;
        background-position: center;
        text-align: center;
        line-height: 44px;
    }

    .custom-checkboxes input[type="checkbox"] + label span div {
        width: 100%;
        height: 100%;
        color: white;
        font-size: 24px;
        font-weight: bold;
        font-family: cursive;
        line-height: 36px;
    }

    .custom-checkboxes input[type="checkbox"] + label span img {
        transition: all .3s ease;
        display: inline-block;
        margin-top: 10px;
    }

    .custom-checkboxes input[type="checkbox"] + label span img.closed {
        margin-top: 5px;
    }

    .custom-checkboxes input[type="checkbox"] + label span {
        background-color: red;
    }

    .custom-checkboxes input[type="checkbox"]:checked + label span {
        background-color: #46b450;
    }

    .custom-checkboxes input[type="checkbox"] label span img {
        opacity: 1;
    }
</style>
