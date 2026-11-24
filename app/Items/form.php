<?php
$values = $data['values'];
$errors = $data['errors'];
$fields = [
    'naam' => ['Naam', 'text', 100],
    'smaak' => ['Smaak', 'text', 100],
    'prijs' => ['Prijs in euro', 'decimal', 7],
    'voorraad' => ['Voorraad', 'numeric', 10],
];
?>
<form class="card item-form" method="post" action="<?= escape($data['target']) ?>">
    <input type="hidden" name="csrf_token" value="<?= escape($data['token']) ?>">
    <?php if ($errors !== []): ?>
        <p class="error" role="alert">Controleer de gemarkeerde velden.</p>
    <?php endif; ?>
    <?php foreach ($fields as $field => [$label, $mode, $maxLength]): ?>
        <div class="field">
            <label for="<?= escape($field) ?>"><?= escape($label) ?></label>
            <input
                id="<?= escape($field) ?>"
                name="<?= escape($field) ?>"
                inputmode="<?= escape($mode) ?>"
                maxlength="<?= escape($maxLength) ?>"
                value="<?= escape($values[$field] ?? '') ?>"
                required
                <?php if (isset($errors[$field])): ?>
                    aria-invalid="true" aria-describedby="<?= escape($field) ?>-error"
                <?php endif; ?>
            >
            <?php if (isset($errors[$field])): ?>
                <p class="error" id="<?= escape($field) ?>-error"><?= escape($errors[$field]) ?></p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <div class="actions">
        <button class="button" type="submit"><?= escape($data['submit']) ?></button>
        <a href="/">Annuleren</a>
    </div>
</form>
