#!/bin/bash
sudo apt-get install apache2
sudo apt-get install libapache2-mod-php7.0
sudo apt-get install sysstat
sudo apt-get install nginx

sudo cp ./000-default.conf /etc/apache2/sites-availible/000-default.conf
sudo cp ./ports.conf /etc/apache2/ports.conf
sudo cp ./default /etc/nginx/sites-availible/default

sudo adduser lgv1
sudo mkdir /home/lgv1/tasks
sudo mkdir /home/lgv1/info
sudo cp ./startDump.sh /home/lgv1/tasks/startDump.sh
sudo cp ./stopDump.sh /home/lgv1/tasks/stopDump.sh
sudo cp ./task1.sh /home/lgv1/tasks

sudo chmod +x /home/lgv1/tasks/startDump.sh
sudo chmod +x /home/lgv1/tasks/stopDump.sh

sudo chown lgv1 /home/lgv1

sudo crontab -u lgv1 ./cttasks

sudo rm -r /var/www/html
sudo cp -r ./html /var/www/


sudo /home/lgv1/tasks/startDump.sh
