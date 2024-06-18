## About the Project

This project is an application/request portal for the StuRa of BTU Cottbus-Senftenberg. It is intended to be used by the students of the university to request a refund of the semesterticket and in the future to request other services from the StuRa.

## Learning Laravel

This project is based on the Laravel PHP framework. If you are interested in learning more about Laravel, please check out the [Laravel documentation](https://laravel.com/docs).

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

Furthermore, this Project uses the following dependencies quite extensively:

- Filament: https://filamentphp.com
- Laravel Livewire: https://livewire.laravel.com
- Tailwindcss: https://tailwindcss.com

## Development Setup

### Prerequisites

You can use Docker Desktop (not Docker) to run the application:

- Docker Desktop: https://www.docker.com/products/docker-desktop

On Windows this requires WSL2 to be installed: https://docs.microsoft.com/en-us/windows/wsl/install

### Installation (using Docker)

0. Only for Windows: Open WSL and navigate to the project directory

```bash
wsl
```

1. Clone the repository

```bash
git clone git@git.informatik.tu-cottbus.de:software-security-app/stura-forms.git
cd stura-forms
```

2. Start the Docker Container

```bash
bin/serve
```

Should there be Permissions issues, please checkout the branch `build` and run the command bin/serve again.

3. Visit the Application

You can find the application at http://localhost:8000.

You can view sent e-mails on http://localhost:8025.

4. Stop the Docker Container

Press `Ctrl+C` in the terminal where you started the Docker container.

## Running Tests

To run tests, the application must be running. You can then run the following command (on Windows in WSL):

```bash
vendor/bin/sail test
```

## Security Vulnerabilities

If you discover a security vulnerability within this Application, please send an e-mail to Julius Kiekbusch via [contact@julius-kiekbusch.de](mailto:contact@julius-kiekbusch.de). All security vulnerabilities will be promptly addressed.

## License

This project was created for the StuRa of BTU Cottbus-Senftenberg by Julius Kiekbusch.

This project is closed source and not available for public use. If you are interested in using this project, please contact Julius Kiekbusch via [contact@julius-kiekbusch.de](mailto:contact@julius-kiekbusch.de)
