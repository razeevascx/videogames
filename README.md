# Videogame Catalogue

A PHP and MySQL CRUD website for managing videogame records. The project uses
Twig to keep server-side logic separate from HTML templates and Bootstrap for
responsive styling.

## Features

- View, add, amend and delete videogames.
- Search by name, release year range and minimum rating.
- AJAX name autocomplete on the catalogue page.
- Prepared MySQL statements to prevent SQL injection.
- Escaped Twig output to reduce XSS risk.
- CSRF tokens on add, amend and delete forms.
- Shared Bootstrap navigation.

## Requirements

- PHP 8+
- MySQL with a `videogames` table
- Composer

## Setup

1. Configure the database environment variables, or update the defaults in
   `db.php`:

   ```text
   DB_HOST=localhost
   DB_USER=your_mysql_user
   DB_PASSWORD=your_mysql_password
   DB_NAME=your_database
   ```

2. Install Twig:

   ```bash
   composer install
   ```

3. Start the local PHP server:

   ```bash
   php -S localhost:8000
   ```

4. Open <http://localhost:8000>.

## Main files

- `index.php` and `index.html` - homepage entry point.
- `list-games.php` and `list-games.html` - catalogue and AJAX autocomplete.
- `search-games.php` and `search-games.html` - multi-criteria search.
- `add-game-form.php` and `add-game-form.html` - add form.
- `edit-game.php` and `edit-game.html` - amend form.
- `game-details.php` and `game-details.html` - record details and deletion.
- `db.php` - MySQL connection only.
- `bootstrap.php` - shared database, session and security setup.
- `view.php` - shared Twig renderer.
- `navigation.html` - shared navigation partial.
