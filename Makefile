all: project/cache project/log project/config/app.yml project/config/databases.yml

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