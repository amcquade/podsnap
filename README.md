# PodSnap - Podcast Search & PWA Installer

A podcast search engine powered by Podcast Index that allows users to install any podcast as a standalone Progressive
Web App (PWA). [Checkout the live version!](https://podsnap.xyz)

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
- Podcast Index API credentials

## Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/amcquade/podsnap.git
   cd podsnap
   ```

2. Environment file (see table below for values):
   ```bash
   touch .env
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

Access the site at: [https://podsnap.lndo.site](https://podsnap.lndo.site)

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

## Using docker
### building and running the image locally
```bash
docker build -t my-lando-app .
docker run -p 8080:80 --name my-lando-container my-lando-app
```
### deleting the container
```bash
docker stop my-lando-container
docker rm my-lando-container
```
### connect to (running) container for debugging
```bash
docker exec -it my-lando-container bash
```

## Configuration

Edit `.lando.yml` to customize your Lando setup. Key services include:

- `appserver`: PHP/Apache service
- `database`: MySQL database

## .env Variables

| Variable         | Description                   |
|------------------|-------------------------------|
| `PCI_API_KEY`    | Your Podcast Index API key    |
| `PCI_API_SECRET` | Your Podcast Index API secret |

## Contributing

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

Distributed under the GNU General Public License v3.0. See `LICENSE` for more information.

## Acknowledgments

- [Podcast Index](https://podcastindex.org/) for their open podcast API
- [Lando](https://lando.dev/) for local development environment
- [HTMX](https://htmx.org/) for lightweight AJAX functionality
- [Howler.js](https://howlerjs.com/) audio player


### Future features / TODOs
- played episodes list
- notifications for new episodes
- offline playback 