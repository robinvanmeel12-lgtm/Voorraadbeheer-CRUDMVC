<h1>Item verwijderen</h1>
<div class="card">
    <p>Wil je <strong><?= escape($data['item']['naam']) ?></strong> definitief verwijderen?</p>
    <form class="actions" method="post" action="/?actie=verwijderen&amp;id=<?= escape($data['item']['id']) ?>">
        <input type="hidden" name="csrf_token" value="<?= escape($data['token']) ?>">
        <button class="button danger" type="submit">Definitief verwijderen</button>
        <a href="/">Annuleren</a>
    </form>
</div>
