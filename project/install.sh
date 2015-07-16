mkdir -p cache log
echo "To change cache and log rights, we need root access through sudo"
sudo chown www-data cache log
sudo chmod g+ws cache log
echo "Rigths set"
cp config/app.yml.example config/app.yml
cp config/databases.yml.example config/databases.yml
cp web/vinsdeloire_dev.php.example web/vinsdeloire_dev.php
