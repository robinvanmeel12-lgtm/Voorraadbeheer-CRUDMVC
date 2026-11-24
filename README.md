# Voorraadbeheer CRUD MVC

Beheer voorraaditems met een naam, smaak, prijs en voorraad. Elk item heeft een automatisch oplopend ID. Het bestaande [DrawSQL-ontwerp](database/ontwerp.jpg) toont de tabel en de velden.

## Lokaal starten

1. Start MySQL en PHP via Herd. Gebruik PHP 8.2 of hoger met de extensies `pdo_mysql` en `mbstring`.
2. Gebruik je bestaande database `voorraadbeheer` als die al de tabel `voorraad` bevat. Voor een verse installatie maak je in phpMyAdmin een lege database `voorraadbeheer` aan, selecteer je die en importeer je [voorraadbeheer.sql](database/voorraadbeheer.sql). De export bevat drie voorbeelditems.
3. Open [voorraadbeheer-crudmvc.test](http://voorraadbeheer-crudmvc.test). Stel bij een eigen webserver `public/` in als documentroot.

`config/database.php` maakt via PDO verbinding met MySQL. De lokale standaardwaarden zijn `localhost`, poort `3306`, database `voorraadbeheer` en gebruiker `root` met een leeg lokaal wachtwoord. Andere waarden stel je in via `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` en `DB_PASS` in de omgeving van het PHP-proces.

Zonder Herd kun je vanuit de projectmap `php -S 127.0.0.1:8080 -t public` uitvoeren. Open daarna [127.0.0.1:8080](http://127.0.0.1:8080).

## MVC

| Bestanden | Rol |
| --- | --- |
| `public/index.php` | Koppelt de aanvraag en HTTP-methode aan een controlleractie. |
| `app/controllers/ItemController.php` | Valideert invoer, kiest de view en geeft resultaatmeldingen. |
| `app/models/Item.php` | Leest en wijzigt de MySQL-tabel en gebruikt prepared statements voor SQL met invoer. |
| `app/Items/` | Toont het overzicht, de formulieren en meldingen. Dynamische tekst wordt geescaped met `htmlspecialchars()`. |

## Expertchallenges

| Hoofdstuk | Uitgevoerd |
| --- | --- |
| H1 Toevoegen | Servervalidatie per veld, invoer behouden bij fouten en CSRF-token verplicht. |
| H2 Tonen | Zoeken op een deel van de naam, met letterlijke procenttekens en underscores. |
| H3 Bewerken | Dezelfde validatie als bij toevoegen, melding bij een onbekend ID en annuleren zonder opslaan. |
| H4 Verwijderen | Bevestigingspagina, CSRF-token bij POST en melding als het item al weg is. |

## Lege voorraad testen

Maak in phpMyAdmin een aparte testdatabase aan als `voorraadbeheer_test` nog niet bestaat.

```sql
CREATE DATABASE voorraadbeheer_test;
CREATE TABLE voorraadbeheer_test.voorraad LIKE voorraadbeheer.voorraad;
```

Start vanuit de projectmap in een aparte PowerShell-terminal een tweede PHP-server.

```powershell
$env:DB_NAME = 'voorraadbeheer_test'
php -S 127.0.0.1:8081 -t public
```

Open [127.0.0.1:8081](http://127.0.0.1:8081) en controleer de melding bij een lege voorraad. Je kunt deze omgeving ook voor de overige tests gebruiken. Stop de testserver met Ctrl+C.
