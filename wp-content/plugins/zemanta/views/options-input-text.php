
<input id="zemanta_options_<?php echo $field; ?>" name="zemanta_options[<?php echo $field; ?>]" size="40" type="text" value="<?php echo isset($option) && !empty($option) ? $option : (isset($default_value) ? $default_value : ''); ?>"<?php if(isset($disabled) && $disabled) : ?> disabled="disabled"<?php endif; ?> />

<?php if (isset($description)): ?>

  <span class="description">
    <?php echo $description; ?>
  </span>

<?php endif; ?>