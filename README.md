## Get Started

This guide will walk you through the steps needed to get this project up and running on your local machine.

### Prerequisites

Before you begin, ensure you have the following installed:

- Docker
- Docker Compose

### Building the Docker Environment

Build and start the containers:

```
docker-compose up -d --build
```

### Installing Dependencies

```
docker-compose exec app sh
composer install
```

### Database Setup

Set up the database:

```
bin/cake migrations migrate
```

Set up seed data:

```
bin/cake migrations seed --seed UsersSeed
bin/cake migrations seed --seed ArticlesSeed
bin/cake migrations seed --seed UserArticleReactionsSeed
```

### Build frontend

Build vue.js frontend:

```
cd webroot/vue
npm install
npm run build
```

### Accessing the Application

The application should now be accessible at http://localhost:34251

## How to check

Please run the migration and seed data before running the test.

Default user: `admin@betamind.test`. Password: `P@ssw0rd`.

Please build the frontend before running the test.

You can test the application on http://betamind.life-adventurer.com/.

For the API test, you can import the Postman collection file: `Betamind.postman_collection.json`.

### Authentication

#### Sign up

1. Open the application on your browser.
2. Click the "Sign up" link.
3. Fill in the form with email and password.
4. Click the "Sign up" button.

Validation:
- Email is required and must be a valid email.
- Password is required and must have a minimum of 8 characters.
- Password confirmation must match the password.

#### Log in

1. Open the application on your browser.
2. Click the "Log in" link.
3. Fill in the form with email and password.
4. Click the "Log in" button.

### Article Management

#### Home page

1. Home page will show the list of articles.
2. Click the "Create new Article" link to create a new article. Need to be logged in to access this feature.
3. Click on the article to view the article detail

#### Create new article

1. Click the "Create new Article" link on the home page.
2. Fill in the form with title and content.
3. Click the "Create" button.
4. The article will be created and redirect to the article detail page.

Validation:
- Title is required and have a maximum of 254 characters.
- Content is required and have a maximum of 3999 characters.

#### Article detail

1. Click on the article on the home page.
2. The article detail page will show the article title, content, the author and like count.
3. Click the "Like" button to like the article. Need to be logged in to access this feature.
4. Click the "Edit" button to edit the article. Need to be logged in and must be the author to access this feature.
5. Click the "Delete" button to delete the article. Need to be logged in and must be the author to access this feature.

#### Article edit

1. Click the "Edit" button on the article detail page.
2. Fill in the form with title and content.
3. Click the "Update" button.
4. The article will be updated and redirect to the article detail page.

Validation:
- Title is required and have a maximum of 254 characters.
- Content is required and have a maximum of 3999 characters.

#### Delete article

1. Click the "Delete" button on the article detail page.
2. The confirmation dialog will show.
3. Click "Yes" to delete the article.
4. The article will be deleted and redirect to the home page. Only the author can delete the article.

### Like Feature

1. Click on the article on the home page.
2. The article detail page will show the article title, content, the author and like count.
3. Click the "Like" button to like the article. Need to be logged in to access this feature.
4. If the user already liked the article, the "Like" button will change to "Liked".
5. User cannot unlike the article.
6. The like count will increase by 1.
7. User can like his own article.
