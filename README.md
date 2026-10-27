# Motorenovace.com

[![Docker](https://github.com/pdostal/motorenovace.com/actions/workflows/docker.yml/badge.svg)](https://github.com/pdostal/motorenovace.com/actions/workflows/docker.yml)
[![Docker Hub](https://img.shields.io/docker/v/pdostal/motorenovace?label=Docker%20Hub&sort=semver)](https://hub.docker.com/r/pdostal/motorenovace)

Motorcycle renovation and travel website running on PHP 8.4 with Apache.

## Run locally

```sh
docker compose up --build
```

Open <http://localhost:3781>.

## Container image

Every push to `master` publishes these tags to Docker Hub:

- `docker.io/pdostal/motorenovace:latest`
- `docker.io/pdostal/motorenovace:sha-<commit>`

Dependabot checks PHP and GitHub Actions weekly. Patch updates merge automatically after a successful image build; minor and major updates require review.

## Image maintenance

- Images: `find . -name '*.jpg' -exec convert '{}' -resize 1280x960 -quality 80 '{}' \;`
