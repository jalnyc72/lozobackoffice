#!/usr/bin/env groovy

node {
	checkout scm

	stage('Dependencies') {
		sh 'composer install --no-scripts --ignore-platform-reqs'
	}

	stage('Test') {
		sh 'docker run -v $(pwd):/var/www/html -w /var/www/html digbang/php-dev:7.1 ./vendor/bin/phpspec run --no-interaction -c phpspec.cc.yml'
	}

	stage('Analyze') {
		sh 'sonar-scanner'
	}
}
