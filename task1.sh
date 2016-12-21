#!/bin/bash

cp /home/lgv1/info/dump.out /home/lgv1/info/dumpold.out
> /home/lgv1/info/dump.out
cp /home/lgv1/info/udpdump.out /home/lgv1/info/udpdumpold.out
> /home/lgv1/info/udpdump.out
cp /home/lgv1/info/tcpdump.out /home/lgv1/info/tcpdumpold.out
> /home/lgv1/info/tcpdump.out

awk '{print $2,$4}' /home/lgv1/info/dumpold.out | rev | cut -c 2- | rev | awk '{if($1<$2){print $1 " " $2}else{print $2 " " $1}}' | sort -u > /home/lgv1/info/allsessions.out
awk '{print $2,$4}' /home/lgv1/info/tcpdumpold.out | rev | cut -c 2- | rev | awk '{if($1<$2){print $1 " " $2}else{print $2 " " $1}}' | sort -u > /home/lgv1/info/tcpsessions.out
awk '{print $2,$4}' /home/lgv1/info/udpdumpold.out | rev | cut -c 2- | rev | awk '{if($1<$2){print $1 " " $2}else{print $2 " " $1}}' | sort -u > /home/lgv1/info/udpsessions.out

for ((c = 0; c < 6; c++))
do
iostat | sed '/^$/d'| sed '1,2d' > /home/lgv1/info/io.out
cat /proc/net/dev | grep : > /home/lgv1/info/netstat.out
df -h -i > /home/lgv1/info/fsstat.out
sleep 10
done
