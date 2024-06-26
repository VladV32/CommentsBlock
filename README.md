# Comments Block

## About

This project provides a web application for managing user comments. Users can create, leave comments.

## Installation

1. Clone the repository:

   `git clone <repository-url>`

2. Navigate to the project directory:

   `cd <project-directory>`

3. Install PHP dependencies:

   `composer install`

4. Copy the example environment file and set up your environment variables:

   `cp .env.example .env`

5. Generate an application key:

   `php artisan key:generate`

6. Start the Docker containers:

   `./vendor/bin/sail up -d`

7. Run database migrations to ensure the database schema is up-to-date:

   `./vendor/bin/sail artisan migrate`

8. Generate Swagger documentation:

   `./vendor/bin/sail artisan l5-swagger:generate`

9. Generate IDE helper files (optional but recommended for development):

   `./vendor/bin/sail artisan ide-helper:generate`

10. Generate Web interface
   
   `./vendor/bin/sail npm install`

   `./vendor/bin/sail npm run build`

   (optional but recommended for development): 
   `./vendor/bin/sail npm run dev`

11. Run query workers

   `./vendor/bin/sail artisan queue:work`

## Usage

   Open url: http://your-project-url/
   
   or for development url: http://localhost/

## Endpoints

### Comments

- GET `/api/comments`: Retrieve a list of all comments.
- POST `/api/comments`: Create a new comment. (Authentication required)
- PATCH `/api/comments/{id}`: Update details of a specific comment. (Authentication required)
- DELETE `/api/comments/{id}`: Delete a specific comment. (Authentication required)

## Swagger Documentation

Access the API documentation at:

`http://your-project-url/api/documentation`

## Contributing

Contributions are welcome! Please feel free to submit issues or pull requests.

## License

This project is licensed under the [MIT license](https://opensource.org/licenses/MIT).
