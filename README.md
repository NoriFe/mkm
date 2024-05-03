# Product API

This is a simple API for managing products. It allows you to retrieve a product by its SKU.

## Setup

1. Clone the repository:

    ```bash
    git clone https://github.com/NoriFe/mkm.git
    ```

2. Navigate to the project directory:

    ```bash
    cd yourrepository
    ```

3. Install the dependencies:

    ```bash
    composer install
    ```

4. Copy the `.env.example` file to create your own environment file:

    ```bash
    cp .env.example .env
    ```

5. Generate an application key:

    ```bash
    php artisan key:generate
    ```

6. Create a new database and update the `.env` file with your database credentials.

7. Run the migrations to create the necessary tables:

    ```bash
    php artisan migrate
    ```



## Usage

To start the server, run:

```bash
php artisan serve
```

## API

This will start the server at `http://localhost:8000`.

### Retrieving a Product

To retrieve a product by its SKU, you'll need to send a GET request to `/api/products/{sku}`, replacing `{sku}` with the actual SKU. 

You'll also need to include an `Authorization` header with a valid API token. Here's how you can do this with cURL:

```bash
curl -H "Accept: application/json" -H "Authorization: Bearer your-api-token" http://localhost:8000/api/products/your-sku