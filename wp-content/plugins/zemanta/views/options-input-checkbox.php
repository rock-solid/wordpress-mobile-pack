
<input id="zemanta_options_<?php echo $field; ?>" name="zemanta_options[<?php echo $field; ?>]" type="checkbox" value="1" <?php echo isset($option) && !empty($option) ? 'checked="checked"' : ''; ?><?php if(isset($disabled) && $disabled) : ?> disabled="disabled"<?php endif; ?>  />

<?php if (isset($title)): ?>

  <label for="zemanta_options_<?php echo $field; ?>">
    <?php echo $title; ?>
  </label>

<?php endif; ?>

<?php if (isset($description)): ?>

  <p>
    <?php echo $description; ?>
  </p>

<?php endif; ?>