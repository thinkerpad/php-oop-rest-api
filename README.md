# PHP OOP REST API

## Project URL
https://php-oop-rest-api.onrender.com/

## Overview
This project is a RESTful API built using PHP with an Object-Oriented Programming (OOP) approach. It allows users to manage quotes, authors, and categories through CRUD (Create, Read, Update, Delete) operations. The API interacts with a database to store data and returns responses in JSON format. It was developed as a midterm project to demonstrate skills in PHP, OOP, and REST API design.

## Key Features
- Manage quotes with filtering by author and category.
- Perform CRUD operations on authors and categories.
- Returns all responses in JSON format.
- Uses PDO for secure database interactions (MySQL/PostgreSQL).

## API Endpoints

### Quotes
- `GET /api/quotes/`: Get all quotes or filter by `author_id` and/or `category_id`.
- `GET /api/quotes/?id={id}`: Get a single quote by ID.
- `POST /api/quotes/`: Create a quote (requires `quote`, `author_id`, `category_id`).
- `PUT /api/quotes/`: Update a quote (requires `id`, `quote`, `author_id`, `category_id`).
- `DELETE /api/quotes/`: Delete a quote (requires `id`).

### Authors
- `GET /api/authors/`: Get all authors.
- `GET /api/authors/?id={id}`: Get a single author by ID.
- `POST /api/authors/`: Create an author (requires `author`).
- `PUT /api/authors/`: Update an author (requires `id`, `author`).
- `DELETE /api/authors/`: Delete an author (requires `id`).

### Categories
- `GET /api/categories/`: Get all categories.
- `GET /api/categories/?id={id}`: Get a single category by ID.
- `POST /api/categories/`: Create a category (requires `category`).
- `PUT /api/categories/`: Update a category (requires `id`, `category`).
- `DELETE /api/categories/`: Delete a category (requires `id`).