all: project/cache project/log project/config/app.yml project/config/databases.yml project/web/declaration_dev.php project/web/components/vins/vins-preview.html

project/cache:
	mkdir project/cache
	chmod g+sw,o+w project/cache

project/log:
	mkdir project/log
	chmod g+sw,o+w project/log

project/config/app.yml:
	cp project/config/app.yml.example project/config/app.yml

project/config/databases.yml:
	cp project/config/databases.yml.example project/config/databases.yml

project/web/declaration_dev.php:
	cp project/web/declaration_dev.php.example project/web/declaration_dev.php

project/web/components/vins/vins-preview.html: project/web/components/vins/fontcustom.yml project/web/components/vins/svg/bouteille.svg  project/web/components/vins/svg/mouts.svg  project/web/components/vins/svg/raisins.svg  project/web/components/vins/svg/vrac.svg
	cd project/web/components/vins ; fontcustom compile -c fontcustom.yml
