
<?php if(isset($title)): ?>
<label class="option-label" for="zemanta_options_<?php echo $field; ?>"><?php echo $title; ?></label>
<?php endif; ?>

<input id="zemanta_options_<?php echo $field; ?>" class="regular-text code" name="zemanta_options[<?php echo $field; ?>]" size="40" type="text" value="<?php echo isset($option) && !empty($option) ? $option : (isset($default_value) ? $default_value : ''); ?>"<?php if(isset($disabled) && $disabled) : ?> disabled="disabled"<?php endif; ?>  />

<?php if (isset($description)): ?>

  <p class="description">
    <?php echo $description; ?>
  </p>

<?php endif; ?>