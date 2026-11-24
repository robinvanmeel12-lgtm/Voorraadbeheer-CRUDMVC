<?php

declare(strict_types=1);

final class ItemController
{
    public function __construct(private Item $items)
    {
    }

    public function index(): void
    {
        $search = $_GET['zoek'] ?? '';
        $search = is_string($search) ? trim($search) : '';
        $notice = $_SESSION['notice'] ?? '';
        unset($_SESSION['notice']);

        $this->render('index', [
            'title' => 'Voorraadoverzicht',
            'items' => $this->items->all($search),
            'search' => $search,
            'notice' => $notice,
        ]);
    }

    public function create(): void
    {
        $this->showCreate([], []);
    }

    public function store(): void
    {
        if (!$this->checkToken()) {
            return;
        }

        [$values, $errors] = $this->validate($_POST);
        if ($errors !== []) {
            http_response_code(422);
            $this->showCreate($values, $errors);
            return;
        }

        $this->items->create($values);
        $this->redirect('Item toegevoegd.');
    }

    public function edit(): void
    {
        $id = $this->itemId();
        $item = $id === null ? null : $this->items->find($id);
        if ($item === null) {
            $this->showMissing();
            return;
        }

        $this->showEdit($id, $item, []);
    }

    public function update(): void
    {
        if (!$this->checkToken()) {
            return;
        }

        $id = $this->itemId();
        if ($id === null) {
            $this->showMissing();
            return;
        }

        [$values, $errors] = $this->validate($_POST);
        if ($errors !== []) {
            if ($this->items->find($id) === null) {
                $this->showMissing();
                return;
            }

            http_response_code(422);
            $this->showEdit($id, $values, $errors);
            return;
        }

        if (!$this->items->update($id, $values)) {
            $this->showMissing();
            return;
        }

        $this->redirect('Item bijgewerkt.');
    }

    public function confirmDelete(): void
    {
        $id = $this->itemId();
        $item = $id === null ? null : $this->items->find($id);
        if ($item === null) {
            $this->showMissing();
            return;
        }

        $this->render('delete', [
            'title' => 'Item verwijderen',
            'item' => $item,
        ]);
    }

    public function delete(): void
    {
        if (!$this->checkToken()) {
            return;
        }

        $id = $this->itemId();
        if ($id === null || !$this->items->delete($id)) {
            $this->showMissing();
            return;
        }

        $this->redirect('Item verwijderd.');
    }

    private function itemId(): ?int
    {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 2147483647],
        ]);
        return $id === false ? null : $id;
    }

    private function showMissing(): void
    {
        http_response_code(404);
        $this->render('message', [
            'title' => 'Item niet gevonden',
            'message' => 'Dit item bestaat niet meer of het gekozen ID is ongeldig.',
        ]);
    }

    private function showEdit(int $id, array $values, array $errors): void
    {
        $this->render('edit', [
            'title' => 'Item bewerken',
            'values' => $values,
            'errors' => $errors,
            'target' => '/?actie=bewerken&id=' . $id,
            'submit' => 'Wijzigingen opslaan',
        ]);
    }

    private function showCreate(array $values, array $errors): void
    {
        $this->render('create', [
            'title' => 'Item toevoegen',
            'values' => $values,
            'errors' => $errors,
            'target' => '/?actie=toevoegen',
            'submit' => 'Toevoegen',
        ]);
    }

    private function validate(array $input): array
    {
        $values = [];
        $errors = [];

        foreach (['naam', 'smaak', 'prijs', 'voorraad'] as $field) {
            $value = $input[$field] ?? '';
            $values[$field] = is_string($value) ? trim($value) : '';
        }

        foreach (['naam' => 'naam', 'smaak' => 'smaak'] as $field => $label) {
            if ($values[$field] === '' || !mb_check_encoding($values[$field], 'UTF-8')
                || mb_strlen($values[$field], 'UTF-8') > 100) {
                $errors[$field] = "Vul een $label van 1 tot 100 tekens in.";
            }
        }

        $price = str_replace(',', '.', $values['prijs']);
        if (!preg_match('/\A[0-9]{1,4}(?:\.[0-9]{1,2})?\z/', $price)) {
            $errors['prijs'] = 'Vul een prijs van 0,00 tot 9999,99 in, met maximaal twee decimalen.';
        }

        $stock = $values['voorraad'];
        if (!preg_match('/\A[0-9]{1,10}\z/', $stock) || (float) $stock > 2147483647) {
            $errors['voorraad'] = 'Vul een geheel getal van 0 tot 2147483647 in.';
        }

        if ($errors === []) {
            $values['prijs'] = $price;
        }

        return [$values, $errors];
    }

    private function checkToken(): bool
    {
        $expected = $_SESSION['csrf_token'] ?? '';
        $received = $_POST['csrf_token'] ?? '';
        if (is_string($received) && $expected !== '' && hash_equals($expected, $received)) {
            return true;
        }

        http_response_code(403);
        $this->render('message', [
            'title' => 'Formulier verlopen of ongeldig',
            'message' => 'Open het formulier opnieuw en probeer het nog een keer.',
        ]);
        return false;
    }

    private function redirect(string $notice): void
    {
        $_SESSION['notice'] = $notice;
        header('Location: /', true, 303);
    }

    private function render(string $view, array $data): void
    {
        $_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));
        $data['token'] = $_SESSION['csrf_token'];
        require __DIR__ . '/../Items/layout.php';
    }
}
