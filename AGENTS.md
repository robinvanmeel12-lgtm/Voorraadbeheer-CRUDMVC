# Zoekafspraak

Gebruik voor zoeken op een deel van een tekst in MySQL `LIKE` met een `%` vóór en na de zoekterm, bijvoorbeeld `%appel%`. Geef het patroon via een gebonden parameter mee aan een PDO prepared statement. Behoud bij het zoeken op naam de bestaande escaping van letterlijke procenttekens en underscores.
