#!/bin/bash
set -e
cd "$(git rev-parse --show-toplevel)"

if [ -d "/chassis" ]; then
	phpunit -c phpunit.xml
else [ ! -d "/chassis" ]
	echo 'Logging into vagrant...'
	/usr/local/bin/vagrant ssh e293c73 -- -t 'cd /chassis/; sh .tests/.bin/phpunit.sh;'
fi
