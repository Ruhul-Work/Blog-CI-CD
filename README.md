# Laravel Docker + GitHub Actions CI/CD Workflow

## 📉 Objective
Automate the build and deployment of a Laravel application using Docker and GitHub Actions CI/CD, and push the Docker image to Docker Hub.

---

## ✅ Step 1: Project Setup

1. Initialize a Laravel project (or use an existing one).
2. Initialize Git and push to GitHub:

```bash
git init
git remote add origin https://github.com/yourusername/your-repo.git
git add .
git commit -m "Initial commit"
git push -u origin main
```

---

## ✅ Step 2: Docker Support

### Dockerfile
Create a `Dockerfile` in the project root:

```Dockerfile
FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite

WORKDIR /var/www/html
COPY . .

RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

EXPOSE 80
```

### docker-compose.yml
Create a `docker-compose.yml`:

```yaml
version: '3.8'

services:
  app:
    build: .
    ports:
      - "8000:80"
    volumes:
      - .:/var/www/html
    depends_on:
      - mysql
    networks:
      - laravel

  mysql:
    image: mysql:8.0
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: your DB name
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql
    networks:
      - laravel

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    restart: always
    ports:
      - "8080:80"  # Access at http://localhost:8080
    environment:
      PMA_HOST: mysql
      MYSQL_ROOT_PASSWORD: root
    depends_on:
      - mysql
    networks:
      - laravel

volumes:
  db_data:

networks:
  laravel:

```

### .env
Update Laravel `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=root
DB_PASSWORD=root
```

---

## ✅ Step 3: Run Locally

```bash
docker-compose up --build -d
docker exec -it blog-app-1 bash
php artisan key:generate
php artisan migrate
```

---

## ✅ Step 4: Push Docker Image to Docker Hub

```bash
docker login
docker tag blog-app yourdockerhub/laravel-app:latest
docker push yourdockerhub/laravel-app:latest
```

---

## ✅ Step 5: GitHub Actions CI/CD Setup

### .github/workflows/docker-build.yml
Create the workflow file:

```yaml
name: Build & Push Docker Image

on:
  push:
    branches: [ main ]

jobs:
  docker-build:
    runs-on: ubuntu-latest

    steps:
      - name: Checkout code
        uses: actions/checkout@v3

      - name: Log in to Docker Hub
        uses: docker/login-action@v2
        with:
          username: ${{ secrets.DOCKER_USERNAME }}
          password: ${{ secrets.DOCKER_PASSWORD }}

      - name: Build Docker image
        run: docker build -t yourdockerhub/laravel-app:latest .

      - name: Push to Docker Hub
        run: docker push yourdockerhub/laravel-app:latest
```

---

## ✅ Step 6: Add Secrets to GitHub

In your GitHub repo:
- Go to **Settings > Secrets and variables > Actions**
- Add two secrets:

| Name             | Value                         |
|------------------|-------------------------------|
| `DOCKER_USERNAME`| Your Docker Hub username      |
| `DOCKER_PASSWORD`| Your Docker Hub password/token|

---

## ✅ Step 7: Trigger Workflow

After committing and pushing the workflow:

```bash
git add .
git commit -m "Add GitHub Actions Docker workflow"
git push origin main
```

GitHub Actions will:
- Checkout your code
- Build Docker image
- Push it to Docker Hub

---

## 🎉 Success!

You now have:
- 🚣 Laravel app containerized
- ♻️ Automatic image builds on push
- 📦 Connect phpmyadmin on Docker
- 📦 Image published to Docker Hub (`yourdockerhub/laravel-app:latest`)

Ready for deployment or further automation!

