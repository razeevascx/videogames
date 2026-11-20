# Videogame Catalogue

A PHP and MySQL CRUD website for managing videogame records. The project uses
Twig to keep server-side logic separate from HTML templates and Bootstrap for
responsive styling.

## Features

- View, add, amend and delete videogames.
- Search by name, release year range and minimum rating.
- Filter by developer/publisher, genre and platform using saved metadata, and
  sort by newest release, title or rating. Filters combine and can be cleared.
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
   `config/database.php`:

   ```text
   DB_HOST=127.0.0.1
   DB_PORT=3307
   DB_USER=your_mysql_user
   DB_PASSWORD=your_mysql_password
   DB_NAME=your_database
   ```

   The defaults connect to the local Docker MySQL container on port `3307`.
   Use `127.0.0.1` for a TCP connection; `localhost` uses a Unix socket in PHP.
   Environment variables must be exported before starting the PHP server.

2. Create the catalogue table by importing `database/schema.sql` into your database.

3. Install Twig:

   ```bash
   composer install
   ```

4. Start the local PHP server:

   ```bash
   npm start
   ```

5. Open <http://localhost:8000>.

For a deployment, set `SITE_URL` in `.env` to the public site origin (for example,
`https://games.example.com`). This is used when generating the sitemap and its
robots.txt reference.

## Project structure

```text
app/
  bootstrap.php          Shared database, session and CSRF setup
  controllers/           PHP page and action handlers
  support/view.php       Twig renderer
  views/                 Page templates
    components/            Navigation and shared form
config/
  database.php           MySQL connection and environment settings
database/
  schema.sql             Database table definition
public/
  css/                   Stylesheets
  js/                    Theme and modal scripts
  images/                Logo and other images
scripts/
  seed-games.php         Optional sample catalogue data
index.php                Front controller and development-server router
```

Run `npm start` from the project root. The router preserves the existing page
URLs, serves assets from `public/`, and returns the themed 404 page for unknown
URLs. Configure other web servers to route requests through `index.php` and
serve static files only from `public/`.

`/sitemap.xml` is generated from the home page and saved catalogue games;
`/robots.txt` points crawlers to it. Page descriptions and the SVG favicon use
the catalogue logo in `public/images/`.

To add the sample catalogue, run `php scripts/seed-games.php`. Existing games
are preserved; ratings are sample data.

## RAWG search

1. Create an API key at <https://rawg.io/apidocs>.
2. Copy `.env.example` to `.env` and set `RAWG_API_KEY`. The PHP app reads this
   file directly; the key is never sent to the browser. Exported environment
   variables take precedence. Database settings can also be set in `.env`.
3. Run `php scripts/migrate.php` once. This creates `rawg_cache` in the existing
   database and adds an optional `rawg_id` to `videogames`. It does not fetch or
   update existing games. For a fresh database, import `database/schema.sql` first.
4. Open Add game or Search games and use **Find a game on RAWG**. Select a result,
   review the filled fields and save it to add it to the catalogue.

RAWG requests run in PHP. Search responses and game details are cached in the
same MySQL database for 24 hours. API errors are not cached. Opening a saved
game’s details page automatically resolves its RAWG match and loads metadata,
using the cache first. Only that game is linked; the home page never bulk-fetches
metadata. A match must have the same title and release year. Saved descriptions,
dates and ratings are preserved.

After adding a game, the app redirects directly to its details page, including
artwork, platforms, genres and screenshots when available. If RAWG is unavailable,
the page still displays saved data and any previously cached metadata. Cards
show cached artwork, with the catalogue logo as a fallback.

Run `php tests/rawg.php` to check metadata normalization and image URL validation.

The Filter page includes **View more**. Each click requests up to four additional
RAWG matches through PHP and appends them to the current grid or list. Requests
respect genre, platform, developer/publisher, release year and sorting; minimum
ratings are checked against RAWG ratings converted to the catalogue’s ten-point
scale. Existing catalogue games are skipped. Batches reuse the database cache,
and remote results can be opened before choosing **Add to catalogue**.
