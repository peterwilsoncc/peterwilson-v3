[![Build Status](https://travis-ci.org/peterwilsoncc/peterwilson-v3.svg?branch=master)](https://travis-ci.org/peterwilsoncc/peterwilson-v3)

## Set up for local development

Run the following commands

	git clone --recursive git@github.com:peterwilsoncc/peterwilson-v3.git pwcc-v3.local
	cd pwcc-v3.local
	git clone --recursive https://github.com/Chassis/Chassis chassis
	cp bin/config.local.yaml chassis
	cp wp-config-local-sample.php wp-config-local.php
	cd chassis
	git clone --recursive https://github.com/Chassis/Tester.git extensions/tester
	vagrant up

To configure WordPress, visit http://vagrant.local/wp-admin/ in your browser.
