# Comments Block

## About

This project provides a web application for managing user comments. Users can create and leave comments.

## Installation

1. Clone the repository:

   `git clone <repository-url>`

2. Navigate to the project directory:

   `cd <project-directory>`

3. Copy the example environment file and set up your environment variables:

   `cp .env.example .env`

4. Install PHP dependencies (requires PHP 8.3):

   `composer install`

5. Build and start the project using Make commands:

   `make build && make init && make down && make up && make test`

   - `make down` ensures any running instances are stopped before starting fresh.

   If using Laravel Sail, run:

   `./vendor/bin/sail up -d`

## Usage

Open the project URL: https://your-project-url/

For local development, use: https://localhost/

## Documentation

Access the API documentation at:

`https://your-project-url/docs`

## Contributing

Contributions are welcome! Please feel free to submit issues or pull requests.

## License

This project is licensed under the [MIT license](https://opensource.org/licenses/MIT).

