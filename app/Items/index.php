<?php

/**
 * @var array{
 *     notice: string,
 *     search: string,
 *     items: list<array{id: int, naam: string, smaak: string, prijs: string, voorraad: int}>
 * } $data
 */
?>
<div class="page-heading">
    <h1>Voorraadoverzicht</h1>
    <a class="button" href="/?actie=toevoegen">Item toevoegen</a>
</div>

<?php if ($data['notice'] !== ''): ?>
    <p class="notice" role="status"><?= escape($data['notice']) ?></p>
<?php endif; ?>

<form class="search" method="get" action="/">
    <div>
        <label for="zoek">Zoeken op naam</label>
        <input id="zoek" name="zoek" type="search" value="<?= escape($data['search']) ?>">
    </div>
    <button class="button secondary" type="submit">Zoeken</button>
    <?php if ($data['search'] !== ''): ?>
        <a href="/">Zoekopdracht wissen</a>
    <?php endif; ?>
</form>

<?php if ($data['items'] === []): ?>
    <p class="empty">
        <?= $data['search'] === '' ? 'Er staan nog geen items in de voorraad.' : 'Geen items gevonden.' ?>
    </p>
<?php else: ?>
    <div class="table-card" role="region" aria-label="Voorraaditems" tabindex="0">
        <table>
            <thead>
                <tr>
                    <th scope="col">Naam</th>
                    <th scope="col">Smaak</th>
                    <th scope="col" class="numeric">Prijs</th>
                    <th scope="col" class="numeric">Voorraad</th>
                    <th scope="col">Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['items'] as $item): ?>
                    <tr>
                        <th scope="row"><?= escape($item['naam']) ?></th>
                        <td><?= escape($item['smaak']) ?></td>
                        <td class="numeric">€ <?= escape(number_format((float) $item['prijs'], 2, ',', '.')) ?></td>
                        <td class="numeric"><?= escape($item['voorraad']) ?></td>
                        <td>
                            <div class="actions row-actions">
                                <a href="/?actie=bewerken&amp;id=<?= escape($item['id']) ?>">Bewerken</a>
                                <form class="form-inline" method="get" action="/">
                                    <input type="hidden" name="actie" value="bevestigen">
                                    <input type="hidden" name="id" value="<?= escape($item['id']) ?>">
                                    <button class="text-button" type="submit">Verwijderen</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
