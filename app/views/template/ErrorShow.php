<?php

if(isset($data))
{
    foreach ($data as $key => $value) {
        ?>
        <div class="alert alert-danger">
          <strong>Erreur : </strong><?= $value?>
        </div>

        <?php
    }
}?>
