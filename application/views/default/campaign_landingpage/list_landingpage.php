<select class="select2" id="<?php echo $id_landingpage['id-select']; ?>">
    <?php foreach ($list_chanel as $chanel) { ?>
        <option value="<?php echo $chanel->url . "?id_landingpage=" . $chanel->id ?>" <?php echo ($id_landingpage['id_landingpage'] == $chanel->id) ? ' selected="selected"' : ""; ?>>
            <?php echo $chanel->name; ?>
        </option>
    <?php } ?>
</select>