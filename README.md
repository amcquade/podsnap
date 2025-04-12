# Podcast Search & PWA Installer

A podcast search engine powered by Podcast Index that allows users to install any podcast as a standalone Progressive
Web App (PWA).

![Screenshot](screenshot.png)

## Features

- Search podcasts using the Podcast Index API
- View podcast details and episodes
- Install any podcast as a standalone PWA
- Dark/Light mode toggle
- Responsive design for all devices

## Technologies

- PHP
- HTMX
- Bootstrap 5
- Podcast Index API
- Progressive Web App (PWA) technology

## Prerequisites

- [Lando](https://lando.dev/) for local development
- [Node.js](https://nodejs.org/) (v14+)
- Podcast Index API credentials

## Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/podcast-search.git
   cd podcast-search
   ```

2. Create a config folder and file:
   ```bash
   mkdir config
   touch config.php
   ```

3. Start Lando:
   ```bash
   lando start
   ```

4. Get your Podcast Index API credentials from [podcastindex.org](https://podcastindex.org/) and add them to your `.env`
   file.

## Development

Start the development environment:

```bash
lando start
```

Access the site at: [https://podcast-search.lndo.site](https://podcast-search.lndo.site)

## Lando Commands

### Basic Commands

| Command         | Description             |
|-----------------|-------------------------|
| `lando start`   | Start all services      |
| `lando stop`    | Stop all services       |
| `lando restart` | Restart all services    |
| `lando rebuild` | Rebuild the environment |

### [CLI](https://docs.lando.dev/cli/)

#### [ssh](https://docs.lando.dev/cli/ssh)

- Shell into a service:
  ```bash
  lando ssh -s appserver
  ```
- To use root:
  ```bash
  lando ssh -s appserver --user root
  ```

#### [logs](https://docs.lando.dev/cli/logs.html)

- Get the logs:
  ```bash
  lando logs
  ```
- Follow the logs and show timestamps:
  ```bash
  lando logs -t -f
  ```
- Show logs for specific services:
  ```bash
  lando logs -s appserver -s database
  ```

### Database

- Access MySQL:
  ```bash
  lando mysql
  ```
- Import a database dump:
  ```bash
  lando db-import database.sql.gz
  ```

## Configuration

Edit `.lando.yml` to customize your Lando setup. Key services include:

- `appserver`: PHP/Apache service
- `database`: MySQL database
- `node`: Node.js service for frontend assets

## Environment Variables

| Variable                   | Description                   |
|----------------------------|-------------------------------|
| `PODCAST_INDEX_API_KEY`    | Your Podcast Index API key    |
| `PODCAST_INDEX_API_SECRET` | Your Podcast Index API secret |
| `BASE_URL`                 | Base URL for PWA manifests    |

## Deployment

1. Build for production:
   ```bash
   lando composer install --no-dev --optimize-autoloader
   ```

2. Deploy to your hosting provider (ensure PWA requirements are met):
    - HTTPS is required
    - Proper MIME types for manifests
    - Service worker support

## Contributing

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

Distributed under the MIT License. See `LICENSE` for more information.

## Acknowledgments

- [Podcast Index](https://podcastindex.org/) for their open podcast API
- [Lando](https://lando.dev/) for local development environment
- [HTMX](https://htmx.org/) for lightweight AJAX functionality