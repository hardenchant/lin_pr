#!/bin/bash

sudo tcpdump -Z lgv1 -l -nnqt 'proto \tcp' > /home/lgv1/info/tcpdump.out &
sudo tcpdump -Z lgv1 -l -nnqt 'proto \udp' > /home/lgv1/info/udpdump.out &
sudo tcpdump -Z lgv1 -l -nqt > /home/lgv1/info/dump.out &
