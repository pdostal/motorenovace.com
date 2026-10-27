# Motorenovace.com

[![Docker](https://github.com/pdostal/motorenovace.com/actions/workflows/docker.yml/badge.svg)](https://github.com/pdostal/motorenovace.com/actions/workflows/docker.yml)
[![Docker Hub](https://img.shields.io/docker/v/pdostal/motorenovace?label=Docker%20Hub&sort=semver)](https://hub.docker.com/r/pdostal/motorenovace)

Motorcycle renovation and travel website running on PHP 8.5 with Apache.

## Run locally

```sh
docker compose up --build
```

Open <http://localhost:3781>.

## Container image

Every push to `master` publishes these tags to Docker Hub:

- `docker.io/pdostal/motorenovace:latest`
- `docker.io/pdostal/motorenovace:sha-<commit>`

Dependabot checks PHP and GitHub Actions weekly. Updates merge automatically after a successful image build.

## Checks

```sh
php tests/check.php
php -S 127.0.0.1:8099 tests/router.php
```

CI runs these plus `php -l` and an HTTP smoke test before publishing the image.

## Image maintenance

- Images: `find . -name '*.jpg' -exec convert '{}' -resize 1280x960 -quality 80 '{}' \;`
