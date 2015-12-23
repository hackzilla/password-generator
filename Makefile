test:
	mkdir -p build/logs
	phpunit -c phpunit.xml.dist
	if [ -e build/logs/clover.xml ]; then php vendor/bin/coveralls -c .coveralls.yml -v; fi;
